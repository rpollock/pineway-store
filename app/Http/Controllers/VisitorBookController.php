<?php

namespace App\Http\Controllers;

use App\Models\BookingHold;
use App\Models\BookingPlayer;
use App\Models\ClubSetting;
use App\Models\Course;
use App\Support\GreenFees;
use App\Support\TeeSheet;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class VisitorBookController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $sheet = $this->sheet($request);

        if ($request->wantsJson()) {
            return response()->json([
                'selected' => $sheet['selected'],
                'courseId' => $sheet['courseId'],
                'monthLabel' => $sheet['monthLabel'],
                'dayRate' => GreenFees::format($sheet['dayRate']),
                'dayRateLabel' => strtolower($sheet['dayRateLabel']),
                'slots' => $sheet['slots'],
            ]);
        }

        $error = session('error');
        if ($request->boolean('expired')) {
            $error = $error ?: 'Your hold expired and that tee time is available again.';
        }

        return view('pages.book', [...$sheet, 'error' => $error]);
    }

    public function hold(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'play_date' => ['required', 'date'],
            'starts_at' => ['required', 'date_format:H:i'],
            'players' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        $starts = Carbon::parse($validated['play_date'].' '.$validated['starts_at']);

        if ($starts->lte(now())) {
            return back()->with('error', 'That tee time has already passed.');
        }

        try {
            $hold = BookingHold::place(
                (int) $validated['course_id'],
                $validated['play_date'],
                $validated['starts_at'],
                (int) $validated['players'],
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('book.checkout', $hold->token);
    }

    public function checkout(string $token): View|RedirectResponse
    {
        $hold = $this->activeHold($token);

        if (! $hold) {
            return $this->expiredRedirect();
        }

        $booking = $hold->booking()->with('course')->first();
        $playDate = Carbon::parse($booking?->play_date);
        $time = $booking?->startsAtLabel() ?? '';

        return view('pages.book-checkout', [
            'hold' => $hold,
            'booking' => $booking,
            'playDate' => $playDate,
            'time' => $time,
            'total' => GreenFees::format(GreenFees::total($playDate, (int) $hold->spots, $time)),
            'rate' => GreenFees::label($playDate, $time),
        ]);
    }

    public function complete(Request $request, string $token): RedirectResponse
    {
        $hold = $this->activeHold($token);

        if (! $hold) {
            return $this->expiredRedirect();
        }

        $companionCount = max(0, (int) $hold->spots - 1);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'companions' => [$companionCount > 0 ? 'required' : 'sometimes', 'array', 'size:'.$companionCount],
            'companions.*' => ['required', 'string', 'max:80'],
        ]);

        $completed = DB::transaction(function () use ($token, $validated) {
            $hold = $this->activeHold($token);

            if (! $hold) {
                return null;
            }

            $booking = $hold->booking()->with(['course', 'players'])->lockForUpdate()->first();

            if (! $booking) {
                return null;
            }

            $names = [$validated['name']];
            foreach (array_values($validated['companions'] ?? []) as $companion) {
                $names[] = trim((string) $companion);
            }

            foreach ($names as $index => $name) {
                BookingPlayer::query()->create([
                    'booking_id' => $booking->id,
                    'guest_name' => $name,
                    'guest_email' => $index === 0 ? $validated['email'] : null,
                    'position' => $booking->players()->count(),
                ]);
            }

            $phone = trim((string) ($validated['phone'] ?? ''));
            if ($phone !== '') {
                $note = 'Visitor phone: '.$phone;
                $booking->notes = filled($booking->notes) ? $booking->notes."\n".$note : $note;
                $booking->save();
            }

            $hold->delete();

            return [$booking->fresh(['course']), $names];
        });

        if (! $completed) {
            return $this->expiredRedirect();
        }

        [$booking, $names] = $completed;
        $playDate = Carbon::parse($booking->play_date);
        $time = $booking->startsAtLabel();

        return redirect()->route('book.confirmed')->with('booking_confirmation', [
            'id' => $booking->id,
            'course' => $booking->course?->name,
            'date' => $playDate->format('l j F Y'),
            'time' => $time,
            'players' => $names,
            'email' => $validated['email'],
            'total' => GreenFees::format(GreenFees::total($playDate, count($names), $time)),
            'rate' => GreenFees::label($playDate, $time),
        ]);
    }

    public function cancel(string $token): RedirectResponse
    {
        $hold = BookingHold::query()->where('token', $token)->first();
        $hold?->release();

        return redirect()->route('book')->with('error', 'Your hold was released.');
    }

    public function confirmed(): View|RedirectResponse
    {
        $confirmation = session('booking_confirmation');

        if (! is_array($confirmation)) {
            return redirect()->route('book');
        }

        return view('pages.book-confirmed', [
            'confirmation' => $confirmation,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function sheet(Request $request): array
    {
        try {
            $selectedDate = Carbon::parse($request->input('date', now()->toDateString()))->startOfDay();
        } catch (\Throwable) {
            $selectedDate = now()->startOfDay();
        }

        $start = now()->startOfDay();
        $settings = ClubSetting::current();
        $courses = Course::query()->orderBy('sort_order')->orderBy('name')->get();

        if (! $settings->multiple_courses) {
            $courses = $courses->take(1);
        }

        $courseId = $request->integer('course') ?: $courses->first()?->id;
        if ($courseId && ! $courses->contains('id', $courseId)) {
            $courseId = $courses->first()?->id;
        }

        $days = collect(range(0, 13))->map(function (int $offset) use ($start) {
            $day = $start->copy()->addDays($offset);

            return [
                'date' => $day->toDateString(),
                'label' => strtoupper($day->format('D')),
                'day' => $day->format('j'),
                'month' => strtoupper($day->format('M')),
            ];
        });

        $selected = $selectedDate->toDateString();
        if (! $days->contains(fn (array $day) => $day['date'] === $selected)) {
            $selected = now()->toDateString();
            $selectedDate = now()->startOfDay();
        }

        return [
            'days' => $days,
            'selected' => $selected,
            'courses' => $courses,
            'courseId' => $courseId,
            'slots' => $courseId ? TeeSheet::visitorSlots($selectedDate, $courseId) : [],
            'monthLabel' => Carbon::parse($selected)->format('l j F'),
            'dayRate' => GreenFees::perPlayer($selectedDate),
            'dayRateLabel' => GreenFees::label($selectedDate),
            'fees' => GreenFees::table(),
        ];
    }

    private function activeHold(string $token): ?BookingHold
    {
        return BookingHold::findActive($token);
    }

    private function expiredRedirect(): RedirectResponse
    {
        return redirect()->route('book', ['expired' => 1]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Support\GreenFees;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'gallery' => $this->galleryImages(),
            'facts' => $this->facts(),
        ]);
    }

    public function course(): View
    {
        return view('pages.course', [
            'facts' => $this->facts(),
            'championship' => $this->championship(),
        ]);
    }

    public function club(): View
    {
        return view('pages.club', [
            'facts' => $this->facts(),
        ]);
    }

    public function visit(): View
    {
        return view('pages.visit', [
            'facts' => $this->facts(),
            'fees' => GreenFees::table(),
        ]);
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'gallery' => $this->galleryImages(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'facts' => $this->facts(),
        ]);
    }

    public function sendEnquiry(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'subject' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('enquiry_sent', true);
    }

    /**
     * @return list<array{src: string, alt: string, caption: string}>
     */
    private function galleryImages(): array
    {
        return [
            ['src' => 'images/course/wakefield-hero.jpg', 'alt' => 'Parkland fairway at Wakefield Golf Club', 'caption' => 'Championship parkland, Woodthorpe'],
            ['src' => 'images/course/wakefield-clubhouse.jpg', 'alt' => 'Clubhouse terrace looking down the opening holes', 'caption' => 'Two loops from the clubhouse'],
            ['src' => 'images/course/wakefield-par3.jpg', 'alt' => 'A short hole with bunkers short of the green', 'caption' => 'The short holes'],
            ['src' => 'images/course/wakefield-fairway.jpg', 'alt' => 'Tree-lined fairway in morning light', 'caption' => 'Tree-lined corridors'],
            ['src' => 'images/course/wakefield-green.jpg', 'alt' => 'A putting surface with the pin in the middle distance', 'caption' => 'Firm, true greens'],
            ['src' => 'images/course/wakefield-autumn.jpg', 'alt' => 'Autumn colour around the course', 'caption' => 'Autumn at Newmillerdam'],
            ['src' => 'images/course/wakefield-putting.jpg', 'alt' => 'Practice putting green beside the clubhouse', 'caption' => 'Beside the clubhouse'],
            ['src' => 'images/course/wakefield-play.jpg', 'alt' => 'A player approaching a green through the trees', 'caption' => 'A second shot in'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function facts(): array
    {
        return [
            'Architect' => 'Alex “Sandy” Herd',
            'Bunkering' => 'Alister MacKenzie, 1912',
            'Founded' => '1891',
            'Opened' => '1911 / 1912',
            'Holes' => '18',
            'Par' => '72',
            'Length' => '6,653 yards',
            'Type' => 'Parkland',
            'Location' => 'Sandal, City of Wakefield',
        ];
    }

    /**
     * @return list<array{number: int, par: int, stroke_index: int, yards: int}>
     */
    private function championship(): array
    {
        $rows = [
            [1, 4, 12, 325],
            [2, 3, 18, 148],
            [3, 4, 16, 336],
            [4, 4, 2, 398],
            [5, 5, 8, 501],
            [6, 4, 14, 350],
            [7, 3, 10, 220],
            [8, 5, 4, 558],
            [9, 4, 6, 418],
            [10, 4, 7, 412],
            [11, 5, 1, 535],
            [12, 4, 17, 302],
            [13, 4, 15, 334],
            [14, 4, 3, 414],
            [15, 4, 5, 378],
            [16, 3, 13, 173],
            [17, 4, 9, 425],
            [18, 4, 11, 426],
        ];

        return array_map(fn (array $row) => [
            'number' => $row[0],
            'par' => $row[1],
            'stroke_index' => $row[2],
            'yards' => $row[3],
        ], $rows);
    }
}

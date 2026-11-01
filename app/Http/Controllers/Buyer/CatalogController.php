<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\FandomCategory;
use App\Models\Merchandise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Katalog utama: Mendukung filter fandom langsung di halaman yang sama.
     */
    public function index(Request $request): View
    {
        $selectedFandom = $request->query('fandom', 'all');
        $categories = FandomCategory::withCount('merchandises')->get();

        $query = Merchandise::with('category')
            ->withSum([
                'preorders as reserved_quota' => fn (Builder $q) => $q->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
            ], 'quantity');

        // Filter fandom di halaman katalog
        if ($selectedFandom !== 'all') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $selectedFandom));
        }

        $merchandises = $query->latest()->get();

        return view('buyer.katalog', compact('merchandises', 'categories', 'selectedFandom'));
    }

    /**
     * Halaman detail spesifik satu Fandom Anime dengan Hero Banner.
     */
    public function fandom(string $slug): View
    {
        $category = FandomCategory::where('slug', $slug)->firstOrFail();
        $allCategories = FandomCategory::all();

        $merchandises = Merchandise::with('category')
            ->where('fandom_category_id', $category->id)
            ->withSum([
                'preorders as reserved_quota' => fn ($query) => $query->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
            ], 'quantity')
            ->latest()
            ->get();

        return view('buyer.fandom-detail', compact('category', 'allCategories', 'merchandises'));
    }

    /**
     * Halaman Showcase Promosi Karakter Live2D Dinamis
     */
    public function characterShowcase(string $character = 'shiroko'): View
    {
        $characterKey = strtolower($character);

        // Database master lore karakter
        $characterData = [
            'shiroko' => [
                'slug' => 'shiroko',
                'name' => 'Shiroko Sunaookami',
                'sub' => 'ABYDOS HIGH SCHOOL • FORECLOSURE TASK FORCE',
                'desc' => 'Gadis berambut perak bertelinga serigala yang gemar bersepeda dan selalu siap melindungi sekolah Abydos. Koleksi fanart eksklusif convention batch ini.',
                'fandom' => 'Blue Archive',
                'keyword' => 'Shiroko',
            ],
            'yuuka' => [
                'slug' => 'yuuka',
                'name' => 'Yuuka Hayase',
                'sub' => 'MILLENNIUM SCIENCE SCHOOL • SEMINAR TREASURER',
                'desc' => 'Bendahara OSIS Seminar Millennium yang teliti, cerdas, dan selalu siap mengelola anggaran dengan sempurna. Koleksi fanart eksklusif convention batch ini.',
                'fandom' => 'Blue Archive',
                'keyword' => 'Yuuka',
            ],
            'saori' => [
                'slug' => 'saori',
                'name' => 'Saori Joumae',
                'sub' => 'ARIUS SATELLITE SCHOOL • ARIUS SQUAD LEADER',
                'desc' => 'Pemimpin Arius Squad yang tangguh dan protektif, kini menikmati liburan musim panas dengan damai. Koleksi fanart eksklusif convention batch ini.',
                'fandom' => 'Blue Archive',
                'keyword' => 'Saori',
            ],
            'midori' => [
                'slug' => 'midori',
                'name' => 'Midori Saiba',
                'sub' => 'MILLENNIUM SCIENCE SCHOOL • GAME DEVELOPMENT CLUB',
                'desc' => 'Illustrator berbakat dan gamer handal dari Game Development Club yang selalu ceria dan penuh imajinasi. Koleksi fanart eksklusif convention batch ini.',
                'fandom' => 'Blue Archive',
                'keyword' => 'Midori',
            ],
        ];

        // Ambil data karakter sesuai URL (default ke shiroko jika karakter tidak ada)
        $character = $characterData[$characterKey] ?? $characterData['shiroko'];

        // Ambil merchandise dari database yang namanya mengandung karakter tersebut
        $merchandises = Merchandise::with('category')
            ->where('name', 'like', '%'.$character['keyword'].'%')
            ->withSum([
                'preorders as reserved_quota' => fn ($query) => $query->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
            ], 'quantity')
            ->latest()
            ->get();

        return view('buyer.character-showcase', compact('character', 'characterKey', 'merchandises'));
    }
}

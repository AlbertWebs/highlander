<?php

namespace App\Http\Controllers;

use App\Models\AboutCoreValue;
use App\Models\AboutFleetImage;
use App\Models\AboutFleetSubsection;
use App\Models\AboutPageSetting;
use App\Models\AboutSafetyPoint;
use App\Models\AboutSustainabilityItem;
use App\Models\AboutTeamRole;
use App\Models\AboutVisionMissionCard;
use App\Models\Article;
use App\Models\Destination;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Mountain;
use App\Models\SafariExperience;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Support\RelatedToursForDestination;
use App\Support\RelatedToursForMountain;
use App\Support\RelatedToursForSafariExperience;
use App\Support\RelatedToursForTour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    protected function seo(string $pageKey): array
    {
        return SeoMeta::metaFor($pageKey);
    }

    public function about(Request $request): View
    {
        $setting = AboutPageSetting::query()->firstOrFail();

        $visionCards = AboutVisionMissionCard::query()->active()->get();
        $coreValues = AboutCoreValue::query()->active()->get();
        $fleetImages = AboutFleetImage::query()->active()->get();
        $fleetSubsections = AboutFleetSubsection::query()->active()->get();
        $teamRoles = AboutTeamRole::query()->active()->get();
        $safetyPoints = AboutSafetyPoint::query()->active()->get();
        $sustainabilityItems = AboutSustainabilityItem::query()->active()->get();

        $aboutTestimonials = Testimonial::query()
            ->active()
            ->where('show_on_about', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->take(9)
            ->get();

        if ($aboutTestimonials->isEmpty()) {
            $aboutTestimonials = Testimonial::query()
                ->active()
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->take(9)
                ->get();
        }

        return view('pages.about', array_merge($this->seo('about'), compact(
            'setting',
            'visionCards',
            'coreValues',
            'fleetImages',
            'fleetSubsections',
            'teamRoles',
            'safetyPoints',
            'sustainabilityItems',
            'aboutTestimonials',
        )));
    }

    public function mountains(Request $request): View
    {
        $items = Mountain::query()->active()->orderBy('name')->paginate(12);

        $featuredTours = Tour::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('pages.mountains', array_merge($this->seo('mountains'), compact('items', 'featuredTours')));
    }

    public function mountainShow(Mountain $mountain): View
    {
        if (! $mountain->is_active) {
            abort(404);
        }

        $pageTitle = $mountain->name.' — '.config('app.name');
        $meta_title = $mountain->name;
        $meta_description = Str::limit(strip_tags((string) ($mountain->description ?? '')), 160) ?: __('Discover :name — trekking and alpine expeditions with Highlanders Nature Trails.', ['name' => $mountain->name]);

        $relatedTours = RelatedToursForMountain::get($mountain, 2);

        return view('pages.mountain-show', compact('mountain', 'pageTitle', 'meta_title', 'meta_description', 'relatedTours'));
    }

    public function exploreAfrica(Request $request): View
    {
        $items = Destination::query()->active()->orderByDesc('sort_order')->paginate(12);

        return view('pages.explore-africa', array_merge($this->seo('explore-africa'), compact('items')));
    }

    public function destinationShow(Destination $destination): View
    {
        if (! $destination->is_active) {
            abort(404);
        }

        $pageTitle = $destination->name.' — '.config('app.name');
        $meta_title = $destination->name;
        $meta_description = Str::limit(strip_tags((string) ($destination->description ?? '')), 160)
            ?: __('Discover :name — safaris and expeditions with Highlanders Nature Trails.', ['name' => $destination->name]);

        $relatedTours = RelatedToursForDestination::get($destination, 2);

        return view('pages.destination-show', compact('destination', 'pageTitle', 'meta_title', 'meta_description', 'relatedTours'));
    }

    public function safari(Request $request): View
    {
        $items = SafariExperience::query()->active()->orderBy('sort_order')->paginate(12);

        return view('pages.safari', array_merge($this->seo('safari'), compact('items')));
    }

    public function safariExperienceShow(SafariExperience $safariExperience): View
    {
        if (! $safariExperience->is_active) {
            abort(404);
        }

        $pageTitle = $safariExperience->title.' — '.config('app.name');
        $meta_title = $safariExperience->title;
        $meta_description = Str::limit(strip_tags((string) ($safariExperience->description ?? '')), 160)
            ?: __('Discover :name — safari styles with Highlanders Nature Trails.', ['name' => $safariExperience->title]);

        $relatedTours = RelatedToursForSafariExperience::get($safariExperience, 2);

        return view('pages.safari-experience-show', compact('safariExperience', 'pageTitle', 'meta_title', 'meta_description', 'relatedTours'));
    }

    public function gallery(Request $request): View
    {
        $categories = GalleryCategory::query()->active()->get();
        $items = GalleryItem::query()
            ->with('category')
            ->active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(500)
            ->get();

        return view('pages.gallery', array_merge($this->seo('gallery'), compact('items', 'categories')));
    }

    public function articles(Request $request): View
    {
        $items = Article::query()->published()->orderByDesc('published_at')->paginate(9);
        $sidebarArticles = Article::query()
            ->published()
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('pages.articles', array_merge($this->seo('articles'), compact('items', 'sidebarArticles')));
    }

    public function articleShow(Article $article): View
    {
        if (! $article->is_active || ! $article->published_at || $article->published_at->isFuture()) {
            abort(404);
        }

        $sidebarArticles = Article::query()
            ->published()
            ->whereKeyNot($article->getKey())
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('pages.article-show', array_merge($this->seo('articles'), compact('article', 'sidebarArticles')));
    }

    public function featuredExperienceShow(Tour $tour): View
    {
        if (! $tour->is_active) {
            abort(404);
        }

        $tour->load(['itineraryDays' => fn ($q) => $q->orderBy('day_number')]);

        $pageTitle = filled($tour->meta_title)
            ? $tour->meta_title
            : $tour->title.' — '.config('app.name');
        $meta_title = filled($tour->meta_title) ? $tour->meta_title : $tour->title;
        $meta_description = filled($tour->meta_description)
            ? $tour->meta_description
            : Str::limit(strip_tags((string) ($tour->description ?? '')), 160);

        $relatedTours = RelatedToursForTour::get($tour, 2);

        return view('pages.experience-show', compact('tour', 'pageTitle', 'meta_title', 'meta_description', 'relatedTours'));
    }

    public function contact(Request $request): View
    {
        $tours = Tour::query()->active()->orderBy('title')->get();
        $contactEmail = SiteSetting::getValue('contact_email', '');
        $contactPhone = SiteSetting::getValue('contact_phone', '');
        $contactAddress = SiteSetting::getValue('contact_address', '');
        $siteHours = SiteSetting::getValue('site_hours', '');
        $contactMapEmbedUrl = SiteSetting::getValue('contact_map_embed_url', '');
        $socialFacebook = SiteSetting::getValue('social_facebook', '');
        $socialInstagram = SiteSetting::getValue('social_instagram', '');
        $socialYoutube = SiteSetting::getValue('social_youtube', '');
        $socialTwitter = SiteSetting::getValue('social_twitter', '');
        $socialTiktok = SiteSetting::getValue('social_tiktok', '');

        return view('pages.contact', array_merge($this->seo('contact'), compact(
            'tours',
            'contactEmail',
            'contactPhone',
            'contactAddress',
            'siteHours',
            'contactMapEmbedUrl',
            'socialFacebook',
            'socialInstagram',
            'socialYoutube',
            'socialTwitter',
            'socialTiktok',
        )));
    }

    public function privacy(): View
    {
        $privacyContent = trim((string) SiteSetting::getValue('privacy_policy_content', ''));

        return view('pages.privacy', array_merge($this->seo('privacy'), compact('privacyContent')));
    }

    public function terms(): View
    {
        $termsContent = trim((string) SiteSetting::getValue('terms_conditions_content', ''));

        return view('pages.terms', array_merge($this->seo('terms'), compact('termsContent')));
    }

    public function photoCredits(): View
    {
        $photoCreditsContent = trim((string) SiteSetting::getValue('photo_credits_content', ''));

        return view('pages.photo-credits', array_merge($this->seo('photo-credits'), compact('photoCreditsContent')));
    }
}

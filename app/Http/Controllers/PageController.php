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
use App\Models\GalleryItem;
use App\Models\Mountain;
use App\Models\SafariExperience;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Tour;
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

        return view('pages.mountains', array_merge($this->seo('mountains'), compact('items')));
    }

    public function exploreAfrica(Request $request): View
    {
        $items = Destination::query()->active()->orderByDesc('sort_order')->paginate(12);

        return view('pages.explore-africa', array_merge($this->seo('explore-africa'), compact('items')));
    }

    public function safari(Request $request): View
    {
        $items = SafariExperience::query()->active()->orderBy('sort_order')->paginate(12);

        return view('pages.safari', array_merge($this->seo('safari'), compact('items')));
    }

    public function gallery(Request $request): View
    {
        $items = GalleryItem::query()->active()->orderBy('sort_order')->paginate(24);

        return view('pages.gallery', array_merge($this->seo('gallery'), compact('items')));
    }

    public function articles(Request $request): View
    {
        $items = Article::query()->published()->orderByDesc('published_at')->paginate(9);

        return view('pages.articles', array_merge($this->seo('articles'), compact('items')));
    }

    public function articleShow(Article $article): View
    {
        if (! $article->is_active || ! $article->published_at || $article->published_at->isFuture()) {
            abort(404);
        }

        return view('pages.article-show', array_merge($this->seo('articles'), compact('article')));
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

        return view('pages.experience-show', compact('tour', 'pageTitle', 'meta_title', 'meta_description'));
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
        return view('pages.privacy', $this->seo('privacy'));
    }

    public function terms(): View
    {
        return view('pages.terms', $this->seo('terms'));
    }

    public function photoCredits(): View
    {
        return view('pages.photo-credits', $this->seo('photo-credits'));
    }
}

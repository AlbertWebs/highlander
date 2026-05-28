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
use App\Support\SafariSeo\SafariStyleSeoComposer;
use App\Support\SafariSeo\TourExperienceSeoComposer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            ->forNavBucket(Tour::NAV_MOUNTAIN_SAFARI)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(4)
            ->get();

        return view('pages.mountains', array_merge($this->seo('mountains'), compact('items', 'featuredTours')));
    }

    public function mountainShow(Mountain $mountain): View
    {
        if (! $mountain->is_active) {
            abort(404);
        }

        $pageTitle = $mountain->name.' - '.config('app.name');
        $meta_title = $mountain->name;
        $meta_description = Str::limit(strip_tags((string) ($mountain->description ?? '')), 160) ?: __('Discover :name - trekking and alpine expeditions with Highlanders Nature Trails.', ['name' => $mountain->name]);

        $mountainTours = RelatedToursForMountain::allForMountain($mountain);
        $relatedTours = $mountainTours->take(8)->values();
        $mountainIntro = $this->mountainLeadIntro($mountain);

        return view('pages.mountain-show', compact(
            'mountain',
            'pageTitle',
            'meta_title',
            'meta_description',
            'relatedTours',
            'mountainTours',
            'mountainIntro',
        ));
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

        $pageTitle = $destination->name.' - '.config('app.name');
        $meta_title = $destination->name;
        $meta_description = Str::limit(strip_tags((string) ($destination->description ?? '')), 160)
            ?: __('Discover :name - safaris and expeditions with Highlanders Nature Trails.', ['name' => $destination->name]);

        $destinationTours = RelatedToursForDestination::allForDestination($destination);
        $relatedTours = $destinationTours->take(8)->values();
        $destinationIntro = $this->destinationLeadIntro($destination);

        return view('pages.destination-show', compact(
            'destination',
            'pageTitle',
            'meta_title',
            'meta_description',
            'relatedTours',
            'destinationTours',
            'destinationIntro',
        ));
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

        $safariExperience->load('galleryImages:id,safari_experience_id,image,sort_order');

        $pageTitle = $safariExperience->title.' - '.config('app.name');
        $meta_title = $safariExperience->title;

        $safariSeo = Cache::remember(
            'safari.style.seo.v1.'.$safariExperience->getKey().'.'.($safariExperience->updated_at?->getTimestamp() ?? 0),
            7200,
            static fn () => SafariStyleSeoComposer::compose($safariExperience)
        );

        $rawDesc = trim(strip_tags((string) ($safariExperience->description ?? '')));
        $meta_description = filled($rawDesc)
            ? Str::limit($rawDesc, 160)
            : Str::limit(
                (string) ($safariSeo['suggested_meta_description'] ?? __('Discover :name - safari styles with Highlanders Nature Trails.', ['name' => $safariExperience->title])),
                160
            );

        $relatedTours = $safariExperience->tours()
            ->active()
            ->with(['itineraryDays' => fn ($query) => $query->orderBy('day_number')])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(6)
            ->get()
            ->sortBy(function (Tour $tour): array {
                if (preg_match('/\bday\s*(\d+)\b/i', (string) $tour->title, $m) === 1) {
                    return [0, (int) $m[1], $tour->title];
                }

                return [1, 9999, $tour->title];
            })
            ->values();
        $otherSafariStyles = SafariExperience::query()
            ->active()
            ->whereKeyNot($safariExperience->getKey())
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(4)
            ->get();
        $mountainExperiences = Tour::query()
            ->active()
            ->whereNotNull('mountain_id')
            ->whereNotIn('id', $relatedTours->pluck('id'))
            ->with('mountain:id,name,slug')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();
        $exploreAfricaExperiences = Tour::query()
            ->active()
            ->whereNotNull('destination_id')
            ->whereNotIn('id', $relatedTours->pluck('id'))
            ->with('destination:id,name,slug')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();
        $testimonials = Testimonial::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $seoJsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                $safariSeo['faq_json_ld'] ?? null,
                [
                    '@type' => 'Service',
                    'name' => $safariExperience->title,
                    'description' => strip_tags((string) $meta_description),
                    'url' => route('safari.show', $safariExperience),
                    'provider' => [
                        '@type' => 'TravelAgency',
                        'name' => config('app.name'),
                    ],
                ],
            ])),
        ];

        return view('pages.safari-experience-show', compact(
            'safariExperience',
            'pageTitle',
            'meta_title',
            'meta_description',
            'relatedTours',
            'otherSafariStyles',
            'mountainExperiences',
            'exploreAfricaExperiences',
            'safariSeo',
            'testimonials',
            'seoJsonLd',
        ));
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

    protected function destinationLeadIntro(Destination $destination): string
    {
        return match ($destination->slug) {
            'mount-kenya' => __('Mount Kenya is Africa’s second-highest massif: glacier-carved valleys, afro-alpine moorlands, and classic trekking routes. Below is the full set of Mount Kenya treks and linked highland cultural experiences we publish for this hub.'),
            'masai-mara', 'maasai-mara' => __('The Maasai Mara is Kenya’s open-sky wildlife theatre: prides on golden grass, cheetah sprints, and seasonal river drama. Every active itinerary in our collection that references the Mara or paired savanna nodes is listed below.'),
            'okavango' => __('The Okavango Delta is one of Africa’s great wetland mosaics: papyrus channels, islands, and predators drawn to water. Browse every published journey below that features the Delta or pairs it with surrounding safari legs.'),
            'drakensberg' => __('The Drakensberg escarpment delivers cathedral peaks, sandstone ramparts, and highland trails. Any current itinerary referencing the Berg appears below so you can compare days and pricing in one place.'),
            'zanzibar-coast' => __('Zanzibar and the Swahili coast add dhow light, spice-town culture, and reef-calm after bush days. Below are all itineraries that mention Zanzibar or the coast in their routing or title.'),
            default => __('Explore :name with Highlanders Nature Trails. We list every active safari or expedition below whose programme copy or title references this region. Compare duration and budget, then enquire to tailor dates.', ['name' => $destination->name]),
        };
    }

    protected function mountainLeadIntro(Mountain $mountain): string
    {
        return match ($mountain->slug) {
            'mount-kenya' => __('Mount Kenya is Africa’s second-highest massif: glacier-carved valleys, afro-alpine moorlands, and classic trekking routes. Below is the full set of treks and linked highland experiences we publish for this peak.'),
            'mount-kilimanjaro', 'kilimanjaro' => __('Kilimanjaro is Africa’s highest free-standing volcano: rainforest, alpine desert, and summit glaciers. Every active itinerary we publish for this mountain is listed below.'),
            default => __('Trek :name with Highlanders Nature Trails. We list every active itinerary below whose programme copy or title references this peak: compare duration and budget, then enquire to tailor dates.', ['name' => $mountain->name]),
        };
    }

    public function featuredExperienceShow(Tour $tour): View
    {
        if (! $tour->is_active) {
            abort(404);
        }

        $tour->load(['itineraryDays' => fn ($q) => $q->orderBy('day_number')]);

        $pageTitle = filled($tour->meta_title)
            ? $tour->meta_title
            : $tour->title.' - '.config('app.name');
        $meta_title = filled($tour->meta_title) ? $tour->meta_title : $tour->title;

        $tourSeo = Cache::remember(
            'tour.experience.seo.v1.'.$tour->getKey().'.'.($tour->updated_at?->getTimestamp() ?? 0),
            7200,
            static fn () => TourExperienceSeoComposer::compose($tour)
        );

        $meta_description = filled($tour->meta_description)
            ? $tour->meta_description
            : (($tourSeo['suggested_meta_description'] ?? '') ?: Str::limit(strip_tags((string) ($tour->description ?? '')), 160));

        $relatedTours = RelatedToursForTour::get($tour, 6);
        $testimonials = Testimonial::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $offer = null;
        if ($tour->price) {
            $offer = [
                '@type' => 'Offer',
                'priceCurrency' => 'USD',
                'price' => (string) $tour->price,
                'availability' => 'https://schema.org/InStock',
                'url' => route('experiences.show', $tour),
            ];
        }

        $product = [
            '@type' => 'Product',
            'name' => $tour->title,
            'description' => strip_tags((string) $meta_description),
            'sku' => $tour->slug,
            'url' => route('experiences.show', $tour),
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name'),
            ],
        ];
        if ($offer !== null) {
            $product['offers'] = $offer;
        }

        $seoJsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                $tourSeo['faq_json_ld'] ?? null,
                $product,
            ])),
        ];

        return view('pages.experience-show', compact(
            'tour',
            'pageTitle',
            'meta_title',
            'meta_description',
            'relatedTours',
            'tourSeo',
            'testimonials',
            'seoJsonLd',
        ));
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

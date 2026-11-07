<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function home()
    {
        return $this->page('marketing.home', [
            'title' => config('features.product_name'),
            'tagline' => config('features.tagline'),
            'modules' => app(\App\Core\Services\ModuleRegistry::class)->all(),
            'plans' => Plan::query()->where('is_active', true)->orderBy('price_monthly')->get(),
        ]);
    }

    public function features()
    {
        return $this->page('marketing.features', [
            'title' => 'Features',
            'modules' => app(\App\Core\Services\ModuleRegistry::class)->all(),
            'groups' => config('features.groups'),
        ]);
    }

    public function modules()
    {
        return $this->page('marketing.modules', [
            'title' => 'Modules',
            'modules' => app(\App\Core\Services\ModuleRegistry::class)->all(),
            'presets' => config('modules.presets'),
        ]);
    }

    public function pricing()
    {
        return $this->page('marketing.pricing', [
            'title' => 'Pricing',
            'plans' => Plan::query()->where('is_active', true)->orderBy('price_monthly')->get(),
        ]);
    }

    public function howItWorks()
    {
        return $this->page('marketing.how-it-works', ['title' => 'How it works']);
    }

    public function liveDemo()
    {
        return $this->page('marketing.live-demo', [
            'title' => 'Live Demo',
            'accounts' => [
                ['label' => 'Platform Admin', 'email' => 'admin@platform.test', 'panel' => 'platform'],
                ['label' => 'Fashion Owner', 'email' => 'owner@demo-fashion.test', 'panel' => 'business'],
                ['label' => 'Fashion Agent', 'email' => 'agent@demo-fashion.test', 'panel' => 'agent'],
                ['label' => 'Fashion Rider', 'email' => 'rider@demo-fashion.test', 'panel' => 'rider'],
                ['label' => 'Gadgets Owner', 'email' => 'owner@demo-gadgets.test', 'panel' => 'business'],
                ['label' => 'Food Owner', 'email' => 'owner@demo-food.test', 'panel' => 'business'],
            ],
        ]);
    }

    public function loginAs(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'panel' => ['required', 'in:platform,business,agent,rider'],
        ]);

        $user = User::query()->where('email', $request->string('email'))->where('is_demo', true)->firstOrFail();
        Auth::login($user);

        return redirect('/'.match ($request->string('panel')->toString()) {
            'platform' => 'platform',
            'agent' => 'agent',
            'rider' => 'rider',
            default => 'app',
        });
    }

    public function integrations()
    {
        return $this->page('marketing.integrations', ['title' => 'Integrations']);
    }

    public function blog()
    {
        return $this->page('marketing.blog', [
            'title' => 'Blog',
            'posts' => BlogPost::query()->where('is_published', true)->latest('published_at')->get(),
        ]);
    }

    public function contact()
    {
        return $this->page('marketing.contact', ['title' => 'Contact']);
    }

    public function legal(string $page)
    {
        $cms = CmsPage::query()->where('slug', $page)->where('locale', app()->getLocale())->first()
            ?? CmsPage::query()->where('slug', $page)->firstOrFail();

        return $this->page('marketing.legal', [
            'title' => $cms->title,
            'cms' => $cms,
        ]);
    }

    protected function page(string $view, array $data = [])
    {
        // Public pages are covered by imrandevbd/drutogoti request profiling (auto).
        return view($view, $data);
    }
}

<?php


namespace App\Livewire\Public;


use Illuminate\Support\Facades\Route;
//use Livewire\Attributes\On;
use Livewire\Component;


class LanguageSwitcher extends Component
{
    public string $current;
    public array $supported;
    public array $supportedLocales;

    public function mount(): void
    {
        $this->current = app()->getLocale();
        $this->supported = array_keys(config('app.locales'));
        $this->supportedLocales = config('app.supported_locales', []);
    }


    public function switchLocale(string $lang)
    {
        if (! in_array($lang, $this->supported, true)) {
            return; // игнорируем неподдерживаемые коды
        }

        $route = Route::current();
        $name = $route?->getName();


// Текущие параметры роута + заменяем lang
        $params = $route?->parameters() ?? [];
        $params[config('locales.route_param', 'lang')] = $lang;

// Сохраняем query‑параметры
        $query = request()->query();

// Если есть имя роута — генерируем URL корректнее
        if ($name) {
            $url = route($name, array_merge($params, $query));
        } else {
// фоллбек: меняем первую часть пути на /{lang}
            $segments = request()->segments();
            if (!empty($segments)) {
                $segments[0] = $lang; // первый сегмент — это {lang}
            }
            $path = implode('/', $segments);
            $url = url($path . (empty($query) ? '' : ('?' . http_build_query($query))));
        }


// навигация без перерисовки всей страницы (для Livewire v3)
        return $this->redirect($url, navigate: true);
    }


    public function render()
    {
        return view('livewire.public.language-switcher');
    }
}

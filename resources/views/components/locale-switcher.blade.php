@props([
    'class' => 'select w-25',
])

<select {{ $attributes->merge(['class' => $class]) }} x-data="localeSwitcher" @change="change">
    <option @selected(app()->getLocale() === 'en') value="en">English</option>
    <option @selected(app()->getLocale() === 'zh_TW') value="zh_TW">繁體中文</option>
</select>

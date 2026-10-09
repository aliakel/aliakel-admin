<footer class="la-footer mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-white px-6 py-3 text-sm text-slate-500">
    <strong>
        {{ trans('admin.developed_by') }}
        <a class="text-sky-700 hover:underline" href="https://engaliakel.com" target="_blank" rel="noopener noreferrer">
            {{ trans('admin.developer_name') }}
        </a>
    </strong>
    <span>&copy; {{ now()->format('Y') }} {{ config('app.name') }}. {{ trans('admin.all_rights_reserved') }}</span>
</footer>

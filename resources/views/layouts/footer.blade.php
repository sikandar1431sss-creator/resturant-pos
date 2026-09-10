<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <span class="text-muted"><i class="fa fa-cutlery" style="color: #f97316;"></i> Restaurant Management Engine v2.0</span>
    </div>
    <strong>&copy; {{ date('Y') }} <a href="{{ route('dashboard') }}" style="color: #f97316; font-weight: 700;">{{ $setting->nama_perusahaan ?? 'Restaurant POS' }}</a>.</strong> All rights reserved.
</footer>
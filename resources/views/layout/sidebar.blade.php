<nav class="sidebar">
  <div class="sidebar-header">
    <img src="{{ URL::asset('assets/images/dgs.png') }}" alt="dgs"  width="70"  >
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      @hasrole(['admin','manager', 'supervisor'])
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="activity"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      @endhasrole
      <li class="nav-item {{ active_class(['sales/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#sales" role="button" aria-expanded="{{ is_active_route(['sales/*']) }}" aria-controls="sales">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Sales</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
       
        <div class="collapse {{ show_class(['sales/*']) }}" id="sales">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('customer.index') }}" class="nav-link {{ active_class(['sales/customer']) }}">Data Customer</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('quotation.custom') }}" class="nav-link {{ active_class(['sales/customer']) }}">Data Quotation</a>
            </li>
            {{-- 
               --}}
            <li class="nav-item">
              <a href="{{ route('other.index') }}" class="nav-link {{ active_class(['sales/other']) }}">Other</a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{ route('sph.index') }}" class="nav-link {{ active_class(['sph/other']) }}">SPH</a>
            </li> --}}
          </ul>
        </div>
      </li>
      {{-- <li class="nav-item nav-category">web apps</li> --}}
      <li class="nav-item {{ active_class(['setting/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="sliders"></i>
          <span class="link-title">Setting</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
       
        <div class="collapse {{ show_class(['email/*']) }}" id="email">
          <ul class="nav sub-menu">
            @hasrole('admin')
            <li class="nav-item">
              <a href="{{ route('setting.user') }}" class="nav-link {{ active_class(['email/inbox']) }}">User</a>
            </li>
            @endhasrole
            @hasrole(['admin','sales'])
            <li class="nav-item">
              <a href="{{ route('user.profile', Auth::user()->id) }}" class="nav-link {{ active_class(['email/read']) }}">User Profile</a>
            </li>
            @endhasrole
            <li class="nav-item">
              <a href="{{ route('setting.select') }}" class="nav-link {{ active_class(['setting']) }}">Setting Select</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ active_class(['/report']) }}">
        <a href="{{ route('report.index')}}" class="nav-link">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Report Tabulasi</span>
        </a>
      </li>
      
    </ul>
  </div>
</nav>
<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Sidebar:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" checked>
          Light
        </label>
      </div>
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark">
          Dark
        </label>
      </div>
    </div>
  </div>
</nav>
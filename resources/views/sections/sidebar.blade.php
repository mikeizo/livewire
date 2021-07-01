<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="sidebar-sticky">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('search') }}">
          <i class="fas fa-search"></i>
          Search
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('my-numbers') }}">
          <i class="fas fa-hashtag"></i>
          My Numbers
        </a>
      </li>
      @role('admin')
      <li class="nav-item">
        <a class="nav-link collapsed" href="#submenu1" data-toggle="collapse" data-target="#users">
          <i class="fas fa-users"></i>
          Users
          <i class="fas fa-caret-down"></i>
        </a>
        <div class="collapse" id="users" aria-expanded="false">
          <ul class="flex-column pl-3 nav">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('all-numbers') }}">All Numbers</a>
              </li>
          </ul>
        </div>
      </li>
      @endrole
    </ul>
  </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="{{ route('admin') }}">Admin Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin_girl') }}">Girls</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin_man') }}">Men</a>
      </li>
      @if(Auth::User()->role == 4)
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin_membership') }}">Memberships</a>
        </li>
      @endif
      <li class="nav-item">
        <a class="nav-link" href="{{ route('adminChatHistoryIndex') }}">Chat Histories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin_letter') }}">Letters</a>
      </li>
      @if(Auth::User()->role == 4)
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin_sign') }}">Signs</a>
        </li>
      @endif
      @if(Auth::User()->role == 4)
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin_agent') }}">Agents</a>
        </li>
      @endif
    </ul>
  </div>
</nav>
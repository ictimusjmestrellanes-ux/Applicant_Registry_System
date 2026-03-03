<nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm dashboard-navbar">

    <div class="container-fluid">

        <!-- Right User Menu -->
        <ul class="navbar-nav ms-auto align-items-center">

            <!-- User Dropdown -->
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle user-menu"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle me-1"></i>

                    {{ auth()->user()->name ?? 'User' }}

                </a>


                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li class="dropdown-header">
                        Signed in as
                        <strong>
                            {{ auth()->user()->name ?? 'User' }}
                        </strong>
                    </li>


                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>
                            Profile
                        </a>
                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="dropdown-item text-danger">

                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>
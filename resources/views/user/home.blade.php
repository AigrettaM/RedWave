<h1>
    Halo, {{ Auth::user()->name }}
</h1>

<form method="POST" action="{{ route('logout') }}">
@csrf
@method('DELETE')
<button class="btn btn-danger" type="submit">
    Logout
</button>
</form>

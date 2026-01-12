@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="px-4 py-5 text-center bg-dark text-white" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1560243867-5d09f7a731be?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="py-5">
      <h1 class="display-4 fw-bold">SmartPress Solutions</h1>
      <p class="fs-4 mb-4">Premium Quality Application & Printing Services in Bangladesh</p>
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        @auth
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-lg px-4">Go to Dashboard</a>
            @else
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light btn-lg px-4">Go to Dashboard</a>
            @endif
        @else
            <a href="{{ route('user.login') }}" class="btn btn-warning btn-lg px-4 gap-3 fw-bold text-dark">Get Started</a>
            <a href="#services" class="btn btn-outline-light btn-lg px-4">Our Services</a>
        @endauth
      </div>
    </div>
</div>

<!-- Services Section -->
<div id="services" class="container px-4 py-5">
    <h2 class="pb-2 border-bottom text-center">Our Services</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-5">
            <div class="mb-3 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                    <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                    <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                </svg>
            </div>
            <h3 class="fs-2">Offset Printing</h3>
            <p>High-quality bulk printing for books, magazines, brochures, and corporate stationery. Best for large quantities.</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-5">
            <div class="mb-3 text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                  </svg>
            </div>
            <h3 class="fs-2">Digital Printing</h3>
            <p>Quick and vibrant printing for flyers, business cards, and short-run projects. Ideal for urgent needs.</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-5">
            <div class="mb-3 text-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                </svg>
            </div>
            <h3 class="fs-2">Packaging</h3>
            <p>Custom packaging solutions including boxes, labels, and bags to make your brand stand out.</p>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- About / Stats Section -->
<div class="bg-light py-5">
    <div class="container px-4 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold lh-1 mb-3">Why Choose SmartPress BD?</h2>
                <p class="lead">We are one of the leading printing presses in Bangladesh, committed to delivering excellence. With state-of-the-art technology and a dedicated team, we ensure your prints are perfect every time.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">Contact Us</button>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4">View Portfolio</button>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1562577309-4932fdd64cd1?q=80&w=1974&auto=format&fit=crop" class="d-block mx-lg-auto img-fluid rounded shadow" alt="Printing Press Machine" width="700" height="500" loading="lazy">
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
      <li class="nav-item"><a href="#services" class="nav-link px-2 text-muted">Services</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
    </ul>
    <p class="text-center text-muted">© 2025 SmartPress BD, Inc</p>
</footer>
@endsection

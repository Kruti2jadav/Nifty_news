<footer class="mt-5 pt-4 pb-3 border-top">
    <div class="container">

        {{-- Full footer for guests and users with role "user" --}}
        @if(!auth()->check() || auth()->user()->role === 'user')
            <div class="row">

                <!-- Brand -->
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold" style="color:#6f42c1;">NiftyNews</h5>
                    <p class="text-muted small">
                        Real-time market insights, financial news, and intelligent volume tracking
                        to help investors make smarter decisions.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-2 mb-3">
                    <h6 class="fw-semibold">Markets</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-muted text-decoration-none">NIFTY 50</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Sensex</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Top Gainers</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Top Losers</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-md-3 mb-3">
                    <h6 class="fw-semibold">Categories</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-muted text-decoration-none">Business</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Wealth</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Startups & Tech</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Industry & Energy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-3 mb-3">
                    <h6 class="fw-semibold">Connect</h6>
                    <p class="small text-muted mb-2">
                        📧 support@niftynews.com
                    </p>
                    <div>
                        <a href="#" class="text-muted me-3">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="text-muted me-3">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="text-muted">
                            <i class="bi bi-telegram"></i>
                        </a>
                    </div>
                </div>

            </div>

            <hr>
        @endif

        <!-- BOTTOM BAR (VISIBLE TO ALL ROLES & GUESTS) -->
        <div class="d-flex justify-content-between small text-muted">
            <div>
                © {{ date('Y') }} NiftyNews. All rights reserved.
            </div>
            <div>
                <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
            </div>
        </div>

    </div>
</footer>
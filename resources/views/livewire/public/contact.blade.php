    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">{{ __('Get In Touch') }}</h1>
                    <p class="hero-subtitle">
                        {{ __('We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Information Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="product-card text-center h-100">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%); border-radius: 50%;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary-color); font-weight: bold; margin-bottom: 1rem;">{{ __('Visit Our Store') }}</h3>
                    <p style="color: #ccc; line-height: 1.6;">
                        123 Gaming Street<br>
                        Baku, Azerbaijan<br>
                        AZ1000
                    </p>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 1rem;">
                        {{ __('Mon-Sat: 10:00 - 20:00') }}<br>
                        {{ __('Sun: 12:00 - 18:00') }}
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="product-card text-center h-100">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%;">
                        <i class="bi bi-telephone-fill" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary-color); font-weight: bold; margin-bottom: 1rem;">{{ __('Call Us') }}</h3>
                    <p style="color: #ccc; line-height: 1.6;">
                        <a href="tel:+994123456789" style="color: #ccc; text-decoration: none;">+994 12 345 67 89</a><br>
                        <a href="tel:+994501234567" style="color: #ccc; text-decoration: none;">+994 50 123 45 67</a>
                    </p>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 1rem;">
                        {{ __('Sales & Support') }}<br>
                        {{ __('Mon-Fri 9:00-18:00') }}
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="product-card text-center h-100">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 50%;">
                        <i class="bi bi-envelope-fill" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary-color); font-weight: bold; margin-bottom: 1rem;">{{ __('Email Us') }}</h3>
                    <p style="color: #ccc; line-height: 1.6;">
                        <a href="mailto:info@comprador.az" style="color: #ccc; text-decoration: none;">info@comprador.az</a><br>
                        <a href="mailto:support@comprador.az" style="color: #ccc; text-decoration: none;">support@comprador.az</a>
                    </p>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 1rem;">
                        {{ __('We reply within 24 hours') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Form & Map Section -->
        <div class="row mb-5">
            <!-- Contact Form -->
            <div class="col-lg-6 mb-4">
                <div class="product-card h-100">
                    <h2 style="color: var(--primary-color); font-weight: bold; font-size: 2rem; margin-bottom: 2rem;">
                        {{ __('Send us a Message') }}
                    </h2>

                    @if(session('success'))
                        <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; border-radius: 10px;">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; border-radius: 10px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('First Name') }} *</label>
                                <input type="text" class="form-control contact-input @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('Last Name') }} *</label>
                                <input type="text" class="form-control contact-input @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('Email Address') }} *</label>
                            <input type="email" class="form-control contact-input @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control contact-input @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                            <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('Subject') }} *</label>
                            <select class="form-control contact-input @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" required>
                                <option value="">{{ __('Select a subject') }}</option>
                                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>{{ __('General Inquiry') }}</option>
                                <option value="product" {{ old('subject') == 'product' ? 'selected' : '' }}>{{ __('Product Question') }}</option>
                                <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>{{ __('Technical Support') }}</option>
                                <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>{{ __('Order Status') }}</option>
                                <option value="warranty" {{ old('subject') == 'warranty' ? 'selected' : '' }}>{{ __('Warranty Claim') }}</option>
                                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>{{ __('Business Partnership') }}</option>
                            </select>
                            @error('subject')
                            <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label" style="color: #ccc; font-weight: 500;">{{ __('Message') }} *</label>
                            <textarea class="form-control contact-input @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 10px; padding: 15px; font-weight: 600; background: var(--primary-color); border: none;">
                                <i class="bi bi-send-fill me-2"></i>
                                {{ __('Send Message') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map & Additional Info -->
            <div class="col-lg-6 mb-4">
                <div class="product-card h-100">
                    <h3 style="color: var(--primary-color); font-weight: bold; font-size: 1.5rem; margin-bottom: 2rem;">
                        {{ __('Find Us') }}
                    </h3>

                    <!-- Map Placeholder -->
                    <div class="mb-4" style="height: 300px; background: linear-gradient(135deg, #1a1a1a 0%, #333 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-center">
                            <i class="bi bi-geo-alt-fill" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                            <p style="color: #ccc; margin: 0;">{{ __('Interactive Map Coming Soon') }}</p>
                            <small style="color: #999;">{{ __('Visit us at our physical store location') }}</small>
                        </div>
                    </div>

                    <!-- Store Information -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock" style="font-size: 1.2rem; color: var(--primary-color); margin-top: 0.2rem; margin-right: 0.75rem;"></i>
                                <div>
                                    <h6 style="color: white; margin-bottom: 0.5rem;">{{ __('Store Hours') }}</h6>
                                    <p style="color: #ccc; margin: 0; font-size: 0.9rem;">
                                        {{ __('Monday - Friday: 10:00 - 20:00') }}<br>
                                        {{ __('Saturday: 10:00 - 20:00') }}<br>
                                        {{ __('Sunday: 12:00 - 18:00') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-car-front" style="font-size: 1.2rem; color: var(--primary-color); margin-top: 0.2rem; margin-right: 0.75rem;"></i>
                                <div>
                                    <h6 style="color: white; margin-bottom: 0.5rem;">{{ __('Parking') }}</h6>
                                    <p style="color: #ccc; margin: 0; font-size: 0.9rem;">
                                        {{ __('Free parking available in front of the store') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-credit-card" style="font-size: 1.2rem; color: var(--primary-color); margin-top: 0.2rem; margin-right: 0.75rem;"></i>
                                <div>
                                    <h6 style="color: white; margin-bottom: 0.5rem;">{{ __('Payment Methods') }}</h6>
                                    <p style="color: #ccc; margin: 0; font-size: 0.9rem;">
                                        {{ __('Cash, Bank Cards') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; margin-top: 1.5rem;">
                        <h6 style="color: white; margin-bottom: 1rem;">{{ __('Follow Us') }}</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-size: 1.5rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-size: 1.5rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-size: 1.5rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-size: 1.5rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="bi bi-telegram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="product-card mb-5">
            <h2 style="color: var(--primary-color); font-weight: bold; font-size: 2rem; margin-bottom: 2rem; text-align: center;">
                {{ __('Frequently Asked Questions') }}
            </h2>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div style="border-left: 3px solid var(--primary-color); padding-left: 1rem;">
                        <h5 style="color: white; font-weight: 600; margin-bottom: 0.75rem;">{{ __('Do you offer warranty on products?') }}</h5>
                        <p style="color: #ccc; font-size: 0.9rem; margin: 0;">
                            {{ __('Yes, all our products come with manufacturer warranty. Warranty periods vary by product and brand.') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div style="border-left: 3px solid var(--primary-color); padding-left: 1rem;">
                        <h5 style="color: white; font-weight: 600; margin-bottom: 0.75rem;">{{ __('Can I return or exchange products?') }}</h5>
                        <p style="color: #ccc; font-size: 0.9rem; margin: 0;">
                            {{ __('We offer 14-day return policy for unopened products in original packaging. Some restrictions may apply.') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div style="border-left: 3px solid var(--primary-color); padding-left: 1rem;">
                        <h5 style="color: white; font-weight: 600; margin-bottom: 0.75rem;">{{ __('Do you provide technical support?') }}</h5>
                        <p style="color: #ccc; font-size: 0.9rem; margin: 0;">
                            {{ __('Yes, our team provides technical support for all products we sell. Contact us for assistance.') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div style="border-left: 3px solid var(--primary-color); padding-left: 1rem;">
                        <h5 style="color: white; font-weight: 600; margin-bottom: 0.75rem;">{{ __('Do you offer delivery services?') }}</h5>
                        <p style="color: #ccc; font-size: 0.9rem; margin: 0;">
                            {{ __('We provide delivery throughout Baku and other cities in Azerbaijan. Delivery fees may apply based on location.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%); border-radius: 15px; padding: 3rem; text-align: center; margin-bottom: 2rem;">
            <h3 style="color: white; font-weight: bold; font-size: 2rem; margin-bottom: 1rem;">
                {{ __('Ready to Upgrade Your Gaming Setup?') }}
            </h3>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                {{ __('Browse our extensive collection of gaming peripherals and accessories. Find everything you need for the ultimate gaming experience.') }}
            </p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3" style="border-radius: 25px; padding: 12px 30px; font-weight: 600;">
                    {{ __('Browse Products') }}
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg" style="border-radius: 25px; padding: 12px 30px; font-weight: 600;">
                    {{ __('About Us') }}
                </a>
            </div>
        </div>
    </div>


@push('styles')
    <style>
        .hero-section {
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .hero-section > * {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 30px;
        }

        .contact-input {
            background: rgba(255,255,255,0.1) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: #fff !important;
            border-radius: 10px !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease !important;
        }

        .contact-input:focus {
            background: rgba(255,255,255,0.15) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(41, 133, 220, 0.25) !important;
            color: #fff !important;
        }

        .contact-input::placeholder {
            color: #999 !important;
        }

        .contact-input option {
            background: #1a1a1a !important;
            color: #fff !important;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
@endpush

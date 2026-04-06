<section id="contact" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <span class="bh-section-tag">Get in Touch</span>
            <h2>Contact Us</h2>
            <p>Have a question? Send us a message and we'll get back to you shortly</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:28px;text-align:center;height:100%;">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--bh-primary-light, #e8f2ff);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:var(--bh-primary);margin-bottom:14px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h5 style="font-size:16px;font-weight:700;color:var(--bh-dark);margin-bottom:6px;">Phone</h5>
                    <p style="color:#64748b;font-size:14px;margin:0;">{{ $ayar->firma_telefon ?? '' }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:28px;text-align:center;height:100%;">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--bh-primary-light, #e8f2ff);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:var(--bh-primary);margin-bottom:14px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5 style="font-size:16px;font-weight:700;color:var(--bh-dark);margin-bottom:6px;">Email</h5>
                    <p style="color:#64748b;font-size:14px;margin:0;">{{ $ayar->firma_email ?? '' }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:28px;text-align:center;height:100%;">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--bh-primary-light, #e8f2ff);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:var(--bh-primary);margin-bottom:14px;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5 style="font-size:16px;font-weight:700;color:var(--bh-dark);margin-bottom:6px;">Address</h5>
                    <p style="color:#64748b;font-size:14px;margin:0;">{{ $ayar->firma_adres ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center" style="margin-top:20px;">
            <div class="col-lg-8">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:36px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                    @if(session('contact_success'))
                        <div style="padding:14px 18px;border-radius:10px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:14px;margin-bottom:20px;">
                            <i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ session('contact_success') }}
                        </div>
                    @endif
                    <form action="{{ route('contact.submit') }}" method="POST" class="bh-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="bh-input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="bh-input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="bh-input-group">
                                    <i class="fas fa-tag" style="top:20px;"></i>
                                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <textarea name="message" class="form-control" rows="5" placeholder="Your message..." required style="border:2px solid #e2e8f0;border-radius:8px;padding:14px;font-family:'Poppins',sans-serif;font-size:14px;resize:vertical;"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn bh-btn-primary bh-btn-lg w-100">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

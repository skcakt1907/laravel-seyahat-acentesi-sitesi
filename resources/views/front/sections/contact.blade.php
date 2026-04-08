<section id="contact" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <h2>Contact</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="bh-contact-card">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--bh-primary-light, #e8f2ff);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:var(--bh-primary);margin-bottom:14px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h5 style="font-size:16px;font-weight:700;color:var(--bh-dark);margin-bottom:6px;">Phone</h5>
                    <p style="color:#64748b;font-size:14px;margin:0;">{{ $ayar->firma_telefon ?? '' }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="bh-contact-card">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--bh-primary-light, #e8f2ff);display:inline-flex;align-items:center;justify-content:center;font-size:22px;color:var(--bh-primary);margin-bottom:14px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5 style="font-size:16px;font-weight:700;color:var(--bh-dark);margin-bottom:6px;">Email</h5>
                    <p style="color:#64748b;font-size:14px;margin:0;">{{ $ayar->firma_email ?? '' }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="bh-contact-card">
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
                <div class="bh-contact-form-card">
                    @if(session('contact_success'))
                        <div style="padding:14px 18px;border-radius:10px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:14px;margin-bottom:20px;">
                            <i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ session('contact_success') }}
                        </div>
                    @endif
                    <form action="{{ route('contact.submit') }}" method="POST" class="bh-form">
                        @csrf
                        <div style="position:absolute;left:-9999px;"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
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
                                <textarea name="message" class="form-control" rows="5" placeholder="Your message..." required></textarea>
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

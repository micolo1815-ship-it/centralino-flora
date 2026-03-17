<x-layout>
    <main class="contact-page">
        <div class="container">
            <h1 class="oleez-page-title wow fadeInUp">Contact Us</h1>
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0 pr-lg-5 wow fadeInLeft">
                    <div class="embed-responsive embed-responsive-1by1">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7719.8922104486755!2d120.986248!3d14.659!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b429ca9143f7%3A0xd25cdebc345310e8!2sManila%20Central%20University!5e0!3m2!1sen!2sus!4v1733612106511!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
                <div class="col-md-6 pl-lg-5 wow fadeInRight">
                    <form action="POST" class="oleez-contact-form">
                        <div class="form-group">
                            <input type="text" class="oleez-input" id="fullName" name="fullName" required>
                            <label for="fullName">*Full name</label>
                        </div>
                        <div class="form-group">
                            <input type="email" class="oleez-input" id="fullName" name="email" required>
                            <label for="email">*Email</label>
                        </div>
                        <div class="form-group">
                            <label for="message">*Message</label>
                            <textarea name="message" id="message" rows="10" class="oleez-textarea" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layout>

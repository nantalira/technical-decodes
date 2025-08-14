<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files - Online CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@latest/dist/echarts.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.js"></script>
<script src="https://cdn.tiny.cloud/1/stp84faapdbmx3hwtndi4jsshbgla7z8xde6diund1h3y0ix/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>

<!-- NiceAdmin Template Main JS File - Online -->
<script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/js/main.js"></script>

<!-- Custom JS for Job Portal -->
<script>
    // Initialize TinyMCE for job descriptions
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Admin',
            height: 300,
            menubar: false,
            branding: false,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            branding: false,
            // Fix untuk modal Bootstrap
            target_list: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,

            // Handle form submission
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });

                // Custom validation untuk replace HTML5 required
                editor.on('blur', function() {
                    var content = editor.getContent({
                        format: 'text'
                    }).trim();
                    var textarea = editor.getElement();

                    if (content === '') {
                        textarea.setCustomValidity('Job description is required');
                    } else {
                        textarea.setCustomValidity('');
                    }
                });

                // Save content before form submit
                editor.on('SaveContent', function() {
                    editor.save();
                });
            },

            // Initialization callback
            init_instance_callback: function(editor) {
                // Reinitialize dalam modal jika diperlukan
                if (editor.getElement().closest('.modal')) {
                    setTimeout(function() {
                        editor.focus();
                    }, 100);
                }
            }
        });
    }

    // Handle form submission untuk TinyMCE
    document.addEventListener('DOMContentLoaded', function() {
        // Handle semua form yang mengandung TinyMCE
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Trigger save untuk semua TinyMCE instances
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();

                    // Validasi manual untuk TinyMCE fields
                    var isValid = true;
                    tinymce.editors.forEach(function(editor) {
                        var content = editor.getContent({
                            format: 'text'
                        }).trim();
                        var textarea = editor.getElement();

                        // Cek apakah field ini required (berdasarkan form context)
                        var isRequired = textarea.hasAttribute('data-required') ||
                            textarea.closest('form').querySelector('[name="' + textarea
                                .name + '"]');

                        if (isRequired && content === '') {
                            isValid = false;
                            editor.focus();
                            alert('Job description is required');
                            e.preventDefault();
                            return false;
                        }

                        // Update textarea value
                        textarea.value = editor.getContent();
                    });

                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });

        // Reinitialize TinyMCE ketika modal dibuka
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                setTimeout(function() {
                    if (typeof tinymce !== 'undefined') {
                        modal.querySelectorAll('.tinymce-editor').forEach(function(
                            textarea) {
                            if (!tinymce.get(textarea.id)) {
                                tinymce.init({
                                    target: textarea,
                                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    height: 300,
                                    menubar: false,
                                    branding: false
                                });
                            }
                        });
                    }
                }, 100);
            });
        });
    });
    // Job application form enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // File upload preview
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const preview = document.querySelector(`#${this.id}_preview`);
                    if (preview) {
                        preview.textContent =
                            `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    }
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Password Toggle Functionality - Global untuk semua form
        function initPasswordToggles() {
            // Create user modal password toggle
            const createPasswordToggle = document.getElementById('toggleCreateUserPassword');
            if (createPasswordToggle) {
                createPasswordToggle.addEventListener('click', function() {
                    const passwordField = document.getElementById('create_user_password');
                    const eyeIcon = document.getElementById('eyeIconCreateUserPassword');

                    console.log('Toggle clicked:', {
                        passwordField,
                        eyeIcon
                    }); // Debug log

                    if (passwordField && eyeIcon) {
                        const type = passwordField.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        passwordField.setAttribute('type', type);

                        if (type === 'text') {
                            eyeIcon.className = 'bi bi-eye-slash';
                        } else {
                            eyeIcon.className = 'bi bi-eye';
                        }

                        console.log('Password visibility toggled to:', type); // Debug log
                    }
                });
            }

            // Edit user modal password toggle (if exists)
            const editPasswordToggle = document.getElementById('toggleEditPassword');
            if (editPasswordToggle) {
                editPasswordToggle.addEventListener('click', function() {
                    const passwordField = document.getElementById('edit_password');
                    const eyeIcon = document.getElementById('eyeIconEditPassword');

                    const type = passwordField.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    passwordField.setAttribute('type', type);

                    if (type === 'text') {
                        eyeIcon.className = 'bi bi-eye-slash';
                    } else {
                        eyeIcon.className = 'bi bi-eye';
                    }
                });
            }

            // Universal password toggle for any password field with data-toggle attribute
            document.querySelectorAll('[data-password-toggle]').forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const iconId = this.getAttribute('data-icon');
                    const passwordField = document.getElementById(targetId);
                    const eyeIcon = document.getElementById(iconId);

                    if (passwordField && eyeIcon) {
                        const type = passwordField.getAttribute('type') === 'password' ?
                            'text' : 'password';
                        passwordField.setAttribute('type', type);

                        if (type === 'text') {
                            eyeIcon.className = 'bi bi-eye-slash';
                        } else {
                            eyeIcon.className = 'bi bi-eye';
                        }
                    }
                });
            });
        }

        // Initialize password toggles
        initPasswordToggles();

        // Re-initialize when modal is shown (for dynamic content)
        document.addEventListener('shown.bs.modal', function() {
            initPasswordToggles();
        });
    });
</script>

@yield('scripts')

</body>

</html>

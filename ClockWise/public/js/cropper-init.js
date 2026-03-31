console.log('cropper-init.js loaded');
console.log('cropper-init.js loaded');

// Cropper.js initialization for profile photo upload
let cropper;
const input = document.getElementById('photo');
const preview = document.getElementById('photo-preview');
const cropModal = document.getElementById('crop-modal');

function getModalButton(id) {
    return document.getElementById(id);
}

function resetCropperModal() {
    cropModal.style.display = 'none';
    if (cropper) cropper.destroy();
    preview.src = '';
    preview.style.display = 'none';
    input.value = '';
}

if (input) {
    input.addEventListener('change', function (e) {
        console.log('Photo input changed');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                console.log('Showing modal');
                cropModal.style.display = 'flex';
                cropModal.style.border = '4px solid red'; // Debug: make modal border visible
                if (cropper) cropper.destroy();
                cropper = new Cropper(preview, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    background: false,
                });
                // Attach handlers after DOM is updated
                setTimeout(() => {
                    const zoomInBtn = getModalButton('zoom-in-btn');
                    const zoomOutBtn = getModalButton('zoom-out-btn');
                    const cropBtn = getModalButton('crop-btn');
                    const closeBtn = getModalButton('crop-close-btn');
                    if (zoomInBtn) {
                        zoomInBtn.onclick = function () {
                            if (cropper) cropper.zoom(0.1);
                        };
                    }
                    if (zoomOutBtn) {
                        zoomOutBtn.onclick = function () {
                            if (cropper) cropper.zoom(-0.1);
                        };
                    }
                    if (cropBtn) {
                        cropBtn.onclick = function () {
                            if (cropper) {
                                const canvas = cropper.getCroppedCanvas({
                                    width: 300,
                                    height: 300,
                                    imageSmoothingQuality: 'high',
                                });
                                canvas.toBlob(function (blob) {
                                    const file = new File([blob], 'profile.jpg', { type: 'image/jpeg' });
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    input.files = dataTransfer.files;
                                    // Show the cropped image in the profile form preview
                                    const url = URL.createObjectURL(blob);
                                    const profilePreview = document.getElementById('profile-photo-preview');
                                    const profilePlaceholder = document.getElementById('profile-photo-placeholder');
                                    if (profilePreview) {
                                        profilePreview.src = url;
                                        profilePreview.style.display = 'block';
                                    }
                                    if (profilePlaceholder) {
                                        profilePlaceholder.style.display = 'none';
                                    }
                                    resetCropperModal();
                                }, 'image/jpeg');
                            }
                        };
                    }
                    if (closeBtn) {
                        closeBtn.onclick = function () {
                            resetCropperModal();
                        };
                    }
                }, 0);
            };
            reader.readAsDataURL(file);
        }
    });
}
            canvas.toBlob(function (blob) {
                const file = new File([blob], 'profile.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                // Show the cropped image in the profile form preview
                const url = URL.createObjectURL(blob);
                const profilePreview = document.getElementById('profile-photo-preview');
                const profilePlaceholder = document.getElementById('profile-photo-placeholder');
                if (profilePreview) {
                    profilePreview.src = url;
                    profilePreview.style.display = 'block';
                }
                if (profilePlaceholder) {
                    profilePlaceholder.style.display = 'none';
                }
                cropModal.style.display = 'none';
                cropper.destroy();
                preview.src = '';
                preview.style.display = 'none';
            }, 'image/jpeg');
        }
    });
}

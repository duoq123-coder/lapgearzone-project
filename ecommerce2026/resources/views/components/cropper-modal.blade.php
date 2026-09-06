<!-- Cropper.js CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<!-- Modal Crop Ảnh Chung Cho Toàn Hệ Thống -->
<div class="modal fade" id="globalCropModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="cropModalLabel"><i class="bi bi-crop text-primary me-2"></i>Căn chỉnh hình ảnh</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container" style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa; border-radius: 8px;">
                    <img id="imageToCrop" src="" alt="Picture" style="max-width: 100%; display: block;">
                </div>
                <!-- Alignment and Adjustment Toolbar -->
                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnZoomIn" title="Phóng to" data-bs-toggle="tooltip"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnZoomOut" title="Thu nhỏ" data-bs-toggle="tooltip"><i class="bi bi-zoom-out"></i></button>
                    <div class="vr"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRotateLeft" title="Xoay trái" data-bs-toggle="tooltip"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRotateRight" title="Xoay phải" data-bs-toggle="tooltip"><i class="bi bi-arrow-clockwise"></i></button>
                    <div class="vr"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFlipH" title="Lật ngang" data-bs-toggle="tooltip"><i class="bi bi-symmetry-horizontal"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnFlipV" title="Lật dọc" data-bs-toggle="tooltip"><i class="bi bi-symmetry-vertical"></i></button>
                    <div class="vr"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnReset" title="Đặt lại" data-bs-toggle="tooltip"><i class="bi bi-arrow-repeat"></i></button>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="btnCropApply">
                    <i class="bi bi-check2-circle me-1"></i> Áp dụng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    let currentInputFile;
    let currentHiddenInput;
    let currentAspectRatio = 1;
    let scaleX = 1;
    let scaleY = 1;
    let currentPreviewId = null;

    // Initialize tooltips for the toolbar if bootstrap is loaded
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // Hàm gọi tiện ích khởi tạo crop cho bất kỳ thẻ input file nào
    // inputId: ID của thẻ input type="file"
    // hiddenInputId: ID của thẻ input type="hidden" để lưu chuỗi base64 sau khi crop
    // aspectRatio: Tỷ lệ (Ví dụ: 1 cho 1:1, 4/3 cho 4:3, v.v.)
    // previewId (tùy chọn): ID của thẻ img để hiển thị preview ngay sau khi crop
    function initImageCropper(inputId, hiddenInputId, aspectRatio = 1, previewId = null) {
        const inputElement = document.getElementById(inputId);
        if(!inputElement) return;

        inputElement.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                currentInputFile = inputElement;
                currentHiddenInput = document.getElementById(hiddenInputId);
                currentAspectRatio = aspectRatio;
                currentPreviewId = previewId;
                
                const file = files[0];

                if (file.type.startsWith('video/')) {
                    if (currentHiddenInput) {
                        currentHiddenInput.value = '';
                    }
                    return; // Không mở modal crop đối với video
                }

                if (file.type === 'image/gif') {
                    if (previewId) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewEl = document.getElementById(previewId);
                            if (previewEl) previewEl.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                    if (currentHiddenInput) {
                        currentHiddenInput.value = '';
                    }
                    return; // Không mở modal crop đối với ảnh GIF để giữ animation
                }
                
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    const imageToCrop = document.getElementById('imageToCrop');
                    imageToCrop.src = event.target.result;
                    
                    let cropModal = bootstrap.Modal.getInstance(document.getElementById('globalCropModal'));
                    if (!cropModal) {
                        cropModal = new bootstrap.Modal(document.getElementById('globalCropModal'));
                    }
                    cropModal.show();
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    document.getElementById('globalCropModal').addEventListener('shown.bs.modal', function () {
        const imageToCrop = document.getElementById('imageToCrop');
        cropper = new Cropper(imageToCrop, {
            aspectRatio: currentAspectRatio,
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
        scaleX = 1;
        scaleY = 1;
    });

    document.getElementById('globalCropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (currentInputFile) {
            // Reset input file để có thể chọn lại chính file đó nếu hủy
            currentInputFile.value = '';
        }
    });

    document.getElementById('btnCropApply').addEventListener('click', function() {
        if (!cropper) return;
        
        // Lấy chuỗi base64 của ảnh sau khi cắt
        const canvas = cropper.getCroppedCanvas({
            width: 800, // Fixed width max to prevent huge payload
            fillColor: 'transparent',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        const base64Image = canvas.toDataURL('image/png'); // Dùng PNG để giữ nguyên nền trong suốt
        
        // Lưu vào input hidden
        if(currentHiddenInput) {
            currentHiddenInput.value = base64Image;
        }
        
        // Hiển thị preview nếu có thiết lập data attribute (tùy chọn) hoặc từ biến
        const attrPreviewId = currentInputFile.getAttribute('data-preview-id');
        const finalPreviewId = currentPreviewId || attrPreviewId;
        if(finalPreviewId) {
            const previewEl = document.getElementById(finalPreviewId);
            if(previewEl) previewEl.src = base64Image;
        }

        if(currentInputFile) {
            currentInputFile.dispatchEvent(new CustomEvent('cropApply', { detail: { base64: base64Image } }));
        }
        
        // Cập nhật lại UI thông báo đã chọn (vì input file sẽ bị reset)
        currentInputFile.removeAttribute('required');
        
        // Đóng modal
        const modalEl = document.getElementById('globalCropModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        } else {
            // Fallback fallback if getInstance fails
            modal = new bootstrap.Modal(modalEl);
            modal.hide();
        }
        
        // Lưu lại cờ rằng file đã được crop thay vì upload chay
        currentInputFile.value = '';
    });

    // Toolbar Event Listeners
    document.getElementById('btnZoomIn').addEventListener('click', function() {
        if (cropper) cropper.zoom(0.1);
    });
    
    document.getElementById('btnZoomOut').addEventListener('click', function() {
        if (cropper) cropper.zoom(-0.1);
    });
    
    document.getElementById('btnRotateLeft').addEventListener('click', function() {
        if (cropper) cropper.rotate(-45);
    });
    
    document.getElementById('btnRotateRight').addEventListener('click', function() {
        if (cropper) cropper.rotate(45);
    });
    
    document.getElementById('btnFlipH').addEventListener('click', function() {
        if (cropper) {
            scaleX = scaleX === 1 ? -1 : 1;
            cropper.scaleX(scaleX);
        }
    });
    
    document.getElementById('btnFlipV').addEventListener('click', function() {
        if (cropper) {
            scaleY = scaleY === 1 ? -1 : 1;
            cropper.scaleY(scaleY);
        }
    });
    
    document.getElementById('btnReset').addEventListener('click', function() {
        if (cropper) {
            cropper.reset();
            scaleX = 1;
            scaleY = 1;
        }
    });
</script>

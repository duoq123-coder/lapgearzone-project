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
                
                const file = files[0];
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    const imageToCrop = document.getElementById('imageToCrop');
                    imageToCrop.src = event.target.result;
                    
                    const cropModal = new bootstrap.Modal(document.getElementById('globalCropModal'));
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
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        const base64Image = canvas.toDataURL('image/jpeg', 0.85); // Nén jpeg 85%
        
        // Lưu vào input hidden
        if(currentHiddenInput) {
            currentHiddenInput.value = base64Image;
        }
        
        // Hiển thị preview nếu có thiết lập data attribute (tùy chọn)
        const previewId = currentInputFile.getAttribute('data-preview-id');
        if(previewId) {
            const previewEl = document.getElementById(previewId);
            if(previewEl) previewEl.src = base64Image;
        }
        
        // Cập nhật lại UI thông báo đã chọn (vì input file sẽ bị reset)
        currentInputFile.removeAttribute('required');
        
        // Đóng modal
        const modalEl = document.getElementById('globalCropModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        
        // Lưu lại cờ rằng file đã được crop thay vì upload chay
        currentInputFile.value = '';
    });
</script>

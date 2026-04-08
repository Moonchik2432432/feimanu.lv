@extends('layouts.app')

@section('title', 'Pievienot aktualitāti')

@section('content')
<div class="container" style="max-width:850px; margin:40px auto;">

    <h1>Pievienot aktualitāti</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0; border-radius:10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:10px 0;">
            <label>Nosaukums</label><br>
            <input type="text" name="nosaukums"
                   value="{{ old('nosaukums') }}"
                   style="width:100%; padding:8px; box-sizing:border-box;">
        </div>

        <div style="margin:10px 0;">
            <label>Kategorija</label><br>
            <select name="kategorija_id" style="width:100%; padding:8px; box-sizing:border-box;">
                <option value="">-- Izvēlēties --</option>

                @foreach($categories as $cat)
                    <option value="{{ $cat->kategorija_id }}"
                        @selected(old('kategorija_id') == $cat->kategorija_id)>
                        {{ $cat->nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:10px 0;">
            <label>Saturs</label><br>
            <textarea name="saturs" rows="6" style="width:100%; padding:8px; box-sizing:border-box;">{{ old('saturs') }}</textarea>
        </div>

        <div style="margin:20px 0;">
            <label style="display:block; margin-bottom:10px;"><b>Aktualitātes bilde</b></label>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">
                <button type="button" id="btn-upload" style="
                    padding:10px 14px;
                    border:1px solid #ccc;
                    border-radius:10px;
                    background:#f5f5f5;
                    cursor:pointer;
                ">
                    Augšupielādēt no datora
                </button>

                <button type="button" id="btn-gallery" style="
                    padding:10px 14px;
                    border:1px solid #ccc;
                    border-radius:10px;
                    background:#f5f5f5;
                    cursor:pointer;
                ">
                    Izvēlēties no galerijas
                </button>

                <button type="button" id="btn-clear" style="
                    padding:10px 14px;
                    border:1px solid #e0b4b4;
                    border-radius:10px;
                    background:#fff3f3;
                    color:#b40000;
                    cursor:pointer;
                ">
                    Notīrīt fotogrāfiju
                </button>
            </div>

            <div id="upload-box" style="
                display:none;
                padding:16px;
                border:1px solid #ddd;
                border-radius:14px;
                background:#fff;
                margin-bottom:15px;
            ">
                <label style="
                    display:inline-block;
                    padding:10px 14px;
                    border-radius:10px;
                    border:1px solid #ccc;
                    cursor:pointer;
                    background:#f5f5f5;
                ">
                    Izvēlēties failu
                    <input type="file" name="bilde" id="bilde-input" accept="image/*" style="display:none;">
                </label>

                <div style="margin-top:12px; color:#666;">
                    Tiks izmantota bilde no tava datora.
                </div>
            </div>

            <div id="gallery-box" style="
                display:none;
                padding:16px;
                border:1px solid #ddd;
                border-radius:14px;
                background:#fff;
                margin-bottom:15px;
            ">
                <input type="hidden" name="gallery_image" id="gallery_image" value="{{ old('gallery_image') }}">

                <div style="margin-bottom:12px; color:#555;">
                    Izvēlies vienu fotogrāfiju no galerijas:
                </div>

                <div id="gallery-grid" style="
                    display:grid;
                    grid-template-columns:repeat(auto-fill, minmax(150px, 1fr));
                    gap:12px;
                ">
                    @foreach($galleryImages as $index => $img)
                        <div class="gallery-card"
                             data-path="{{ $img->image_path }}"
                             data-src="{{ asset($img->image_path) }}"
                             data-index="{{ $index }}"
                             style="
                                border:2px solid #eee;
                                border-radius:12px;
                                overflow:hidden;
                                background:#fff;
                                cursor:pointer;
                                transition:0.2s;
                             ">
                            <div style="position:relative;">
                                <img src="{{ asset($img->image_path) }}"
                                     alt="{{ $img->title ?: 'Foto' }}"
                                     style="width:100%; height:120px; object-fit:cover; display:block;">

                                <button type="button"
                                        class="open-viewer-btn"
                                        data-index="{{ $index }}"
                                        style="
                                            position:absolute;
                                            right:8px;
                                            bottom:8px;
                                            padding:6px 10px;
                                            border:none;
                                            border-radius:8px;
                                            background:rgba(0,0,0,0.75);
                                            color:#fff;
                                            cursor:pointer;
                                        ">
                                    Skatīt
                                </button>
                            </div>

                            <div style="padding:8px 10px;">
                                <div style="font-size:14px; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $img->title ?: 'Foto #' . $img->id }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="preview-box" style="
                display:none;
                padding:16px;
                border:1px solid #ddd;
                border-radius:14px;
                background:#fff;
            ">
                <div style="margin-bottom:10px; color:#444;">
                    <b>Izvēlētā fotogrāfija</b>
                </div>

                <img id="preview-image"
                     src=""
                     alt="Priekšskatījums"
                     style="
                        width:100%;
                        max-height:380px;
                        object-fit:contain;
                        border-radius:12px;
                        background:#f8f8f8;
                        display:block;
                     ">
            </div>
        </div>

        <button type="submit" style="padding:10px 14px;">Saglabāt</button>
        <a href="{{ route('admin.news') }}" style="margin-left:10px;">Atpakaļ</a>

    </form>
</div>

<div id="viewer-modal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.88);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:30px;
">
    <button type="button" id="viewer-close" style="
        position:absolute;
        top:20px;
        right:20px;
        border:none;
        background:rgba(255,255,255,0.15);
        color:#fff;
        width:42px;
        height:42px;
        border-radius:50%;
        font-size:22px;
        cursor:pointer;
    ">×</button>

    <button type="button" id="viewer-prev" style="
        position:absolute;
        left:20px;
        top:50%;
        transform:translateY(-50%);
        border:none;
        background:rgba(255,255,255,0.15);
        color:#fff;
        width:46px;
        height:46px;
        border-radius:50%;
        font-size:22px;
        cursor:pointer;
    ">‹</button>

    <img id="viewer-image"
         src=""
         alt="Foto"
         style="
            max-width:90vw;
            max-height:80vh;
            border-radius:12px;
            display:block;
         ">

    <button type="button" id="viewer-next" style="
        position:absolute;
        right:20px;
        top:50%;
        transform:translateY(-50%);
        border:none;
        background:rgba(255,255,255,0.15);
        color:#fff;
        width:46px;
        height:46px;
        border-radius:50%;
        font-size:22px;
        cursor:pointer;
    ">›</button>

    <button type="button" id="viewer-select" style="
        position:absolute;
        bottom:25px;
        left:50%;
        transform:translateX(-50%);
        padding:10px 16px;
        border:none;
        border-radius:10px;
        background:#fff;
        color:#222;
        cursor:pointer;
    ">
        Izvēlēties šo fotogrāfiju
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnUpload = document.getElementById('btn-upload');
    const btnGallery = document.getElementById('btn-gallery');
    const btnClear = document.getElementById('btn-clear');

    const uploadBox = document.getElementById('upload-box');
    const galleryBox = document.getElementById('gallery-box');
    const previewBox = document.getElementById('preview-box');
    const previewImage = document.getElementById('preview-image');

    const fileInput = document.getElementById('bilde-input');
    const galleryInput = document.getElementById('gallery_image');

    const galleryCards = document.querySelectorAll('.gallery-card');

    const viewerModal = document.getElementById('viewer-modal');
    const viewerImage = document.getElementById('viewer-image');
    const viewerClose = document.getElementById('viewer-close');
    const viewerPrev = document.getElementById('viewer-prev');
    const viewerNext = document.getElementById('viewer-next');
    const viewerSelect = document.getElementById('viewer-select');
    const openViewerButtons = document.querySelectorAll('.open-viewer-btn');

    const galleryItems = Array.from(galleryCards).map(card => ({
        path: card.dataset.path,
        src: card.dataset.src,
        index: Number(card.dataset.index)
    }));

    let currentViewerIndex = 0;
    let currentMode = null;

    function setButtonActive(btn, active) {
        btn.style.background = active ? '#222' : '#f5f5f5';
        btn.style.color = active ? '#fff' : '#222';
        btn.style.borderColor = active ? '#222' : '#ccc';
    }

    function setButtonDisabled(btn, disabled) {
        btn.disabled = disabled;
        btn.style.opacity = disabled ? '0.55' : '1';
        btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
    }

    function updateCardsSelection() {
        galleryCards.forEach(card => {
            const selected = galleryInput.value && card.dataset.path === galleryInput.value;
            card.style.borderColor = selected ? '#222' : '#eee';
            card.style.boxShadow = selected ? '0 0 0 2px rgba(0,0,0,0.08)' : 'none';
        });
    }

    function showPreview(src) {
        previewImage.src = src;
        previewBox.style.display = 'block';
    }

    function hidePreview() {
        previewImage.src = '';
        previewBox.style.display = 'none';
    }

    function activateUploadMode() {
        currentMode = 'upload';
        uploadBox.style.display = 'block';
        galleryBox.style.display = 'none';

        setButtonActive(btnUpload, true);
        setButtonActive(btnGallery, false);

        setButtonDisabled(btnGallery, fileInput.files.length > 0);

        if (fileInput.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                showPreview(e.target.result);
            };
            reader.readAsDataURL(fileInput.files[0]);
        } else {
            hidePreview();
        }
    }

    function activateGalleryMode() {
        currentMode = 'gallery';
        uploadBox.style.display = 'none';
        galleryBox.style.display = 'block';

        setButtonActive(btnUpload, false);
        setButtonActive(btnGallery, true);

        setButtonDisabled(btnUpload, galleryInput.value !== '');

        const selectedCard = Array.from(galleryCards).find(card => card.dataset.path === galleryInput.value);
        if (selectedCard) {
            showPreview(selectedCard.dataset.src);
        } else {
            hidePreview();
        }
    }

    function resetAll() {
        currentMode = null;

        fileInput.value = '';
        galleryInput.value = '';

        uploadBox.style.display = 'none';
        galleryBox.style.display = 'none';

        setButtonActive(btnUpload, false);
        setButtonActive(btnGallery, false);

        setButtonDisabled(btnUpload, false);
        setButtonDisabled(btnGallery, false);

        hidePreview();
        updateCardsSelection();
    }

    function selectGalleryImage(path, src) {
        galleryInput.value = path;
        fileInput.value = '';

        currentMode = 'gallery';
        galleryBox.style.display = 'block';
        uploadBox.style.display = 'none';

        setButtonActive(btnGallery, true);
        setButtonActive(btnUpload, false);

        setButtonDisabled(btnUpload, true);
        setButtonDisabled(btnGallery, false);

        showPreview(src);
        updateCardsSelection();
    }

    function openViewer(index) {
        currentViewerIndex = index;
        viewerImage.src = galleryItems[currentViewerIndex].src;
        viewerModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeViewer() {
        viewerModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function prevViewer() {
        currentViewerIndex = (currentViewerIndex - 1 + galleryItems.length) % galleryItems.length;
        viewerImage.src = galleryItems[currentViewerIndex].src;
    }

    function nextViewer() {
        currentViewerIndex = (currentViewerIndex + 1) % galleryItems.length;
        viewerImage.src = galleryItems[currentViewerIndex].src;
    }

    btnUpload.addEventListener('click', function () {
        if (btnUpload.disabled) return;
        activateUploadMode();
    });

    btnGallery.addEventListener('click', function () {
        if (btnGallery.disabled) return;
        activateGalleryMode();
    });

    btnClear.addEventListener('click', function () {
        resetAll();
    });

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            galleryInput.value = '';
            currentMode = 'upload';

            setButtonActive(btnUpload, true);
            setButtonActive(btnGallery, false);

            setButtonDisabled(btnGallery, true);
            setButtonDisabled(btnUpload, false);

            uploadBox.style.display = 'block';
            galleryBox.style.display = 'none';

            const reader = new FileReader();
            reader.onload = function(e) {
                showPreview(e.target.result);
            };
            reader.readAsDataURL(fileInput.files[0]);

            updateCardsSelection();
        } else {
            if (currentMode === 'upload') {
                hidePreview();
                setButtonDisabled(btnGallery, false);
            }
        }
    });

    galleryCards.forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.classList.contains('open-viewer-btn')) return;
            selectGalleryImage(this.dataset.path, this.dataset.src);
        });
    });

    openViewerButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            openViewer(Number(this.dataset.index));
        });
    });

    viewerClose.addEventListener('click', closeViewer);
    viewerPrev.addEventListener('click', prevViewer);
    viewerNext.addEventListener('click', nextViewer);

    viewerSelect.addEventListener('click', function () {
        const item = galleryItems[currentViewerIndex];
        selectGalleryImage(item.path, item.src);
        closeViewer();
    });

    viewerModal.addEventListener('click', function (e) {
        if (e.target === viewerModal) {
            closeViewer();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (viewerModal.style.display === 'flex') {
            if (e.key === 'Escape') closeViewer();
            if (e.key === 'ArrowLeft') prevViewer();
            if (e.key === 'ArrowRight') nextViewer();
        }
    });

    @if(old('gallery_image'))
        activateGalleryMode();
        const oldCard = Array.from(galleryCards).find(card => card.dataset.path === @json(old('gallery_image')));
        if (oldCard) {
            selectGalleryImage(oldCard.dataset.path, oldCard.dataset.src);
        }
    @endif
});
</script>

@endsection

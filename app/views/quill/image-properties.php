<!-- Dialog for editing image properties -->
<div id="image-dialog" class="static-card card p-3 shadow" style="position: absolute; top: 50px; right: 50px; z-index: 1000; max-width: 300px; display: none;">
    <div class="mb-3">
        <label for="image-alt" class="form-label">Alt Text:</label>
        <input type="text" id="image-alt" class="form-control">
    </div>

    <div class="mb-3">
        <label for="image-width" class="form-label">Width:</label>
        <input type="number" id="image-width" class="form-control">
    </div>

    <div class="mb-3">
        <label for="image-height" class="form-label">Height:</label>
        <input type="number" id="image-height" class="form-control">
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="maintain-aspect-ratio" checked>
        <label class="form-check-label" for="maintain-aspect-ratio">Maintain Aspect Ratio</label>
    </div>

    <button id="apply-image-properties" class="btn btn-primary">Apply</button>
</div>

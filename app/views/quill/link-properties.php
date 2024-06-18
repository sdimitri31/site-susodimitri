<!-- Dialog for editing link properties -->
<div id="link-dialog" class="static-card card p-3 shadow" style="position: absolute; top: 50px; right: 50px; z-index: 1000; max-width: 300px; display: none;">
    <div class="mb-3">
        <label for="link-url" class="form-label">URL:</label>
        <input type="text" id="link-url" class="form-control">
    </div>
    <div class="mb-3">
        <label for="link-text" class="form-label">Text:</label>
        <input type="text" id="link-text" class="form-control"><br>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="link-target" checked>
        <label class="form-check-label" for="link-target">Open in a new tab</label>
    </div>
    <div class="mb-3">
        <button id="apply-link" class="btn btn-primary">Apply</button>
        <button id="remove-link" class="btn btn-danger">Remove</button>
    </div>
</div>
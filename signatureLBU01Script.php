<!-- Modified Generative AI output. Reference: G, N, T - START -->
<script>
    var canvasFrom = document.getElementById('signature-canvas-from');
    var ctxFrom = canvasFrom.getContext('2d');
    var paintingFrom = false;
    var signatureDataFrom = document.getElementById('signature-data-from');

    var canvasBy = document.getElementById('signature-canvas-by');
    var ctxBy = canvasBy.getContext('2d');
    var paintingBy = false;
    var signatureDataBy = document.getElementById('signature-data-by');

    // Set canvas sizes and initialize white background
    function initCanvas(canvas, ctx) {
        canvas.width = 400;
        canvas.height = 200;
        // Fill with white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        // Set black for drawing
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
    }

    // Initialize both canvases
    initCanvas(canvasFrom, ctxFrom);
    initCanvas(canvasBy, ctxBy);

    // Start position for Received From signature
    function startPositionFrom(e) {
        paintingFrom = true;
        drawFrom(e);
    }

    function endPositionFrom() {
        paintingFrom = false;
        ctxFrom.beginPath();
    }

    function drawFrom(e) {
        if (!paintingFrom) return;

        // Get the canvas position relative to the viewport
        var rect = canvasFrom.getBoundingClientRect();
        var offsetX = e.clientX - rect.left;
        var offsetY = e.clientY - rect.top;

        ctxFrom.lineTo(offsetX, offsetY);
        ctxFrom.stroke();
        ctxFrom.beginPath();
        ctxFrom.moveTo(offsetX, offsetY);
    }

    canvasFrom.addEventListener('mousedown', startPositionFrom);
    canvasFrom.addEventListener('mouseup', endPositionFrom);
    canvasFrom.addEventListener('mousemove', drawFrom);

    // Start position for Received By signature
    function startPositionBy(e) {
        paintingBy = true;
        drawBy(e);
    }

    function endPositionBy() {
        paintingBy = false;
        ctxBy.beginPath();
    }

    function drawBy(e) {
        if (!paintingBy) return;

        // Get the canvas position relative to the viewport
        var rect = canvasBy.getBoundingClientRect();
        var offsetX = e.clientX - rect.left;
        var offsetY = e.clientY - rect.top;

        ctxBy.lineTo(offsetX, offsetY);
        ctxBy.stroke();
        ctxBy.beginPath();
        ctxBy.moveTo(offsetX, offsetY);
    }

    canvasBy.addEventListener('mousedown', startPositionBy);
    canvasBy.addEventListener('mouseup', endPositionBy);
    canvasBy.addEventListener('mousemove', drawBy);

    // Clear buttons for both canvases (now clears to white)
    document.getElementById('clear-btn-from').addEventListener('click', function() {
        ctxFrom.fillStyle = 'white';
        ctxFrom.fillRect(0, 0, canvasFrom.width, canvasFrom.height);
        // Reset drawing settings
        ctxFrom.strokeStyle = 'black';
        ctxFrom.lineWidth = 2;
        ctxFrom.lineCap = 'round';
    });

    document.getElementById('clear-btn-by').addEventListener('click', function() {
        ctxBy.fillStyle = 'white';
        ctxBy.fillRect(0, 0, canvasBy.width, canvasBy.height);
        // Reset drawing settings
        ctxBy.strokeStyle = 'black';
        ctxBy.lineWidth = 2;
        ctxBy.lineCap = 'round';
    });

    // Validate form to ensure signatures are drawn
    function validateForm() {
        // Check if both canvases have signatures
        var imageDataFrom = ctxFrom.getImageData(0, 0, canvasFrom.width, canvasFrom.height);
        var pixelsFrom = imageDataFrom.data;
        var isEmptyFrom = true;

        for (var i = 0; i < pixelsFrom.length; i += 4) {
            // Check if pixel is not white (considering anti-aliasing)
            if (pixelsFrom[i] < 255 || pixelsFrom[i+1] < 255 || pixelsFrom[i+2] < 255) {
                isEmptyFrom = false;
                break;
            }
        }

        var imageDataBy = ctxBy.getImageData(0, 0, canvasBy.width, canvasBy.height);
        var pixelsBy = imageDataBy.data;
        var isEmptyBy = true;

        for (var i = 0; i < pixelsBy.length; i += 4) {
            if (pixelsBy[i] < 255 || pixelsBy[i+1] < 255 || pixelsBy[i+2] < 255) {
                isEmptyBy = false;
                break;
            }
        }

        if (isEmptyFrom || isEmptyBy) {
            alert('Please provide signatures for both "Received From" and "Received By".');
            return false;
        }

        // Set hidden inputs with Base64 data for both signatures as JPG
        signatureDataFrom.value = canvasFrom.toDataURL('image/jpeg');
        signatureDataBy.value = canvasBy.toDataURL('image/jpeg');

        return true;
    }
</script>
<!-- Modified Generative AI output. Reference: G, N, T - END -->
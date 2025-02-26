<script>
    var canvasFrom = document.getElementById('signature-canvas-from');
    var ctxFrom = canvasFrom.getContext('2d');
    var paintingFrom = false;
    var signatureDataFrom = document.getElementById('signature-data-from');

    var canvasBy = document.getElementById('signature-canvas-by');
    var ctxBy = canvasBy.getContext('2d');
    var paintingBy = false;
    var signatureDataBy = document.getElementById('signature-data-by');

    // Set canvas sizes
    canvasFrom.width = 400;
    canvasFrom.height = 200;
    canvasBy.width = 400;
    canvasBy.height = 200;

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

        ctxFrom.lineWidth = 2;
        ctxFrom.lineCap = 'round';
        ctxFrom.strokeStyle = 'black';

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

        ctxBy.lineWidth = 2;
        ctxBy.lineCap = 'round';
        ctxBy.strokeStyle = 'black';

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

    // Clear buttons for both canvases
    document.getElementById('clear-btn-from').addEventListener('click', function() {
        ctxFrom.clearRect(0, 0, canvasFrom.width, canvasFrom.height);
    });

    document.getElementById('clear-btn-by').addEventListener('click', function() {
        ctxBy.clearRect(0, 0, canvasBy.width, canvasBy.height);
    });

    // Validate form to ensure signatures are drawn
    function validateForm() {
        // Check if both canvases have signatures
        var imageDataFrom = ctxFrom.getImageData(0, 0, canvasFrom.width, canvasFrom.height);
        var pixelsFrom = imageDataFrom.data;
        var isEmptyFrom = true;

        for (var i = 0; i < pixelsFrom.length; i += 4) {
            if (pixelsFrom[i + 3] > 0) {
                isEmptyFrom = false;
                break;
            }
        }

        var imageDataBy = ctxBy.getImageData(0, 0, canvasBy.width, canvasBy.height);
        var pixelsBy = imageDataBy.data;
        var isEmptyBy = true;

        for (var i = 0; i < pixelsBy.length; i += 4) {
            if (pixelsBy[i + 3] > 0) {
                isEmptyBy = false;
                break;
            }
        }

        if (isEmptyFrom || isEmptyBy) {
            alert('Please provide signatures for both "Received From" and "Received By".');
            return false;
        }

        // Set hidden inputs with Base64 data for both signatures
        signatureDataFrom.value = canvasFrom.toDataURL();
        signatureDataBy.value = canvasBy.toDataURL();

        return true;
    }
</script>
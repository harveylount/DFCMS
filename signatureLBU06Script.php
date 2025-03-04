<!-- Modified Generative AI output. Reference: G, N - START -->
<style>
    .signature-box {
        border: 1px solid black;
        width: 400px;
        height: 200px;
        margin-bottom: 10px;
    }
    #clear-btn-soco {
        margin-top: 10px;
    }
</style>


<!-- Signature Canvas for Received SOCO -->
<label for="signature">SOCO Signature: *</label><br>
<canvas id="signature-canvas-soco" class="signature-box"></canvas><br>
<button type="button" id="clear-btn-soco">Clear</button><br><br>

<!-- Hidden field to store signature data -->
<input type="hidden" name="signature_data_soco" id="signature-data-soco">

<script>
    var canvasSoco = document.getElementById('signature-canvas-soco');
    var ctxSoco = canvasSoco.getContext('2d');
    var paintingSoco = false;
    var signatureDataSoco = document.getElementById('signature-data-soco');

    // Set canvas sizes
    canvasSoco.width = 400;
    canvasSoco.height = 200;

    // Start position for Received SOCO signature
    function startPositionSoco(e) {
        paintingSoco = true;
        drawSoco(e);
    }

    function endPositionSoco() {
        paintingSoco = false;
        ctxSoco.beginPath();
    }

    function drawSoco(e) {
        if (!paintingSoco) return;

        ctxSoco.lineWidth = 2;
        ctxSoco.lineCap = 'round';
        ctxSoco.strokeStyle = 'black';

        // Get the canvas position relative to the viewport
        var rect = canvasSoco.getBoundingClientRect();
        var offsetX = e.clientX - rect.left;
        var offsetY = e.clientY - rect.top;

        ctxSoco.lineTo(offsetX, offsetY);
        ctxSoco.stroke();
        ctxSoco.beginPath();
        ctxSoco.moveTo(offsetX, offsetY);
    }

    canvasSoco.addEventListener('mousedown', startPositionSoco);
    canvasSoco.addEventListener('mouseup', endPositionSoco);
    canvasSoco.addEventListener('mousemove', drawSoco);

    // Clear button for Received SOCO signature
    document.getElementById('clear-btn-soco').addEventListener('click', function() {
        ctxSoco.clearRect(0, 0, canvasSoco.width, canvasSoco.height);
    });

    // Validate form to ensure signature is drawn
    function validateFormSignature() {
        // Check if canvas has a signature
        var imageDataSoco = ctxSoco.getImageData(0, 0, canvasSoco.width, canvasSoco.height);
        var pixelsSoco = imageDataSoco.data;
        var isEmptySoco = true;

        for (var i = 0; i < pixelsSoco.length; i += 4) {
            if (pixelsSoco[i + 3] > 0) {
                isEmptySoco = false;
                break;
            }
        }

        if (isEmptySoco) {
            alert('Please provide a signature.');
            return false;
        }

        // Set hidden input with Base64 data for the signature
        signatureDataSoco.value = canvasSoco.toDataURL();

        return true;
    }
</script>
<!-- Modified Generative AI output. Reference: G, N - END -->
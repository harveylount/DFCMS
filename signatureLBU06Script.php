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
     initCanvas(canvasSoco, ctxSoco);

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
        ctxSoco.fillStyle = 'white';
         ctxSoco.fillRect(0, 0, canvasSoco.width, canvasSoco.height);
         // Reset drawing settings
         ctxSoco.strokeStyle = 'black';
         ctxSoco.lineWidth = 2;
         ctxSoco.lineCap = 'round';
    });

    // Validate form to ensure signature is drawn
    function validateFormSignature() {
        // Check if canvas has a signature
        var imageDataSoco = ctxSoco.getImageData(0, 0, canvasSoco.width, canvasSoco.height);
        var pixelsSoco = imageDataSoco.data;
        var isEmptySoco = true;

        for (var i = 0; i < pixelsSoco.length; i += 4) {
            if (pixelsSoco[i] < 255 || pixelsSoco[i+1] < 255 || pixelsSoco[i+2] < 255) {
                isEmptySoco = false;
                break;
            }
        }

        if (isEmptySoco) {
            alert('Please provide a signature.');
            return false;
        }

        // Set hidden input with Base64 data for the signature
        signatureDataSoco.value = canvasSoco.toDataURL('image/jpeg');

        return true;
    }
</script>
<!-- Modified Generative AI output. Reference: G, N - END -->
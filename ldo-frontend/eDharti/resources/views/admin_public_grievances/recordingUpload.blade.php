<!-- Modal for Recording Upload -->
<div class="modal fade" id="recordingModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="recordingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="recordingModalLabel">Upload Recording</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Recording Upload File Input -->
        <form id="uploadRecordingForm" action="{{ route('grievance.uploadRecording') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="grievance_id" value="{{ session('grievance_id') }}"> <!-- Hidden input for grievance ID -->
          <div class="form-group">
            <label for="recording" class="form-label">Upload Recording</label>
            <input type="file" name="recording" id="recording" class="form-control" accept="audio/*">
            <div id="recordingError" class="text-danger text-left"></div>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Upload Recording</button>
        </form>
      </div>
    </div>
  </div>
</div>

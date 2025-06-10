@if(isset($personalityTest) && $personalityTest)
    <div class="personality-test-details">
        <div class="alert alert-info mb-3">
            <p class="mb-0"><i class="fas fa-clipboard-check me-2"></i> <strong>Test completed on:</strong> {{ $personalityTest->created_at->format('M d, Y') }}</p>
        </div>

        @if(!empty($personalityTest->summary))
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Personality Overview</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $personalityTest->summary }}</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Personality Traits</h6>
            </div>
            <div class="card-body">
                @php
                    $hasResults = false;
                    $resultsArray = [];
                    
                    // Handle different result formats safely
                    if (!empty($personalityTest->results)) {
                        if (is_array($personalityTest->results)) {
                            $resultsArray = $personalityTest->results;
                            $hasResults = true;
                        } elseif (is_object($personalityTest->results)) {
                            $resultsArray = (array)$personalityTest->results;
                            $hasResults = true;
                        } elseif (is_string($personalityTest->results)) {
                            // Try to decode JSON string
                            try {
                                $decoded = json_decode($personalityTest->results, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $resultsArray = $decoded;
                                    $hasResults = true;
                                } else {
                                    // If not JSON, treat as plain text
                                    $plainText = $personalityTest->results;
                                }
                            } catch (\Exception $e) {
                                // If error decoding, treat as plain text
                                $plainText = $personalityTest->results;
                            }
                        }
                    }
                @endphp
                
                @if($hasResults && count($resultsArray) > 0)
                    <div class="row">
                        @foreach($resultsArray as $key => $value)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="fw-bold">{{ is_numeric($value) ? $value . '%' : $value }}</span>
                                </div>
                                @if(is_numeric($value))
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ min(100, max(0, (int)$value)) }}%;" 
                                             aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif(isset($plainText) && !empty($plainText))
                    <div class="alert alert-primary">
                        <p class="mb-0">{{ $plainText }}</p>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i> No detailed personality test results available.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i> 
                Personality assessments provide insights into candidate's work style and team compatibility.
            </p>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <p class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> No personality test results available for this candidate.</p>
    </div>
@endif 
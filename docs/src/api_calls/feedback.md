{{#include ../header.md}}

# Feedback

> [!WARNING]
> **Experimental.** The `/api/feedback` endpoint is reserved in the BBB server source but is **not routed yet** on current BBB releases — it returns HTTP 404 on BBB 3.x and 4.0-beta servers. This client implementation is provided in anticipation of the endpoint shipping in a future BBB release. Do not rely on it in production, and expect `BadResponseException` until your server actually provides the endpoint.

The feedback endpoint allows users to submit feedback about their meeting experience. This endpoint replaces the old `/html5client/feedback` endpoint with `/api/feedback`, providing a more standardized API approach for collecting user feedback.

This feature is particularly useful for:
- **User Experience Improvement** - Collect structured feedback about meeting quality
- **Quality Assurance** - Monitor user satisfaction and identify issues
- **Analytics** - Gather data for improving the BigBlueButton platform
- **Support** - Allow users to report problems or suggestions

## API Endpoint

```
POST http://yourserver.com/bigbluebutton/api/feedback?[parameters]
```

**Response Format:** JSON (application/json)

## Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `sessionToken` | String | Yes | Session token to identify the user who is submitting feedback |
| `rating` | Integer | No | Numeric rating for the meeting experience (typically 1-5) |
| `comment` | String | No | Textual feedback or comments about the meeting experience |
| `meetingID` | String | No | Meeting ID for which the feedback is being submitted |
| `userID` | String | No | User ID of the person submitting the feedback |

## Usage Examples

### Basic Feedback Submission

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\FeedbackParameters;

$bbb = new BigBlueButton();

// Submit basic feedback with just a session token
$feedbackParams = new FeedbackParameters('user-session-token-123');

$response = $bbb->feedback($feedbackParams);

if ($response->success()) {
    echo "Feedback submitted successfully!";
    echo "Feedback ID: " . $response->getFeedbackID();
    echo "Status: " . $response->getStatus();
} else {
    echo "Error: " . $response->getMessage();
}
```

### Complete Feedback Submission

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\FeedbackParameters;

$bbb = new BigBlueButton();

// Create comprehensive feedback
$feedbackParams = new FeedbackParameters('session-token-456');
$feedbackParams->setRating(4);
$feedbackParams->setComment('Great meeting overall! Audio quality was excellent, but video could be smoother.');
$feedbackParams->setMeetingID('weekly-team-meeting-001');
$feedbackParams->setUserID('participant-john-doe');

$response = $bbb->feedback($feedbackParams);

if ($response->success()) {
    echo "Feedback submitted successfully!";
    echo "Feedback ID: " . $response->getFeedbackID();
    echo "Submitted at: " . $response->getSubmittedAt();
    echo "Processed: " . ($response->getProcessed() ? 'Yes' : 'No');
} else {
    echo "Error: " . $response->getMessage();
    echo "Status Code: " . $response->getStatusCode();
}
```

### Mobile App Feedback

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\FeedbackParameters;

$bbb = new BigBlueButton();

// Mobile app feedback with additional context
$feedbackParams = new FeedbackParameters('mobile-session-token-789');
$feedbackParams->setRating(5);
$feedbackParams->setComment('Excellent mobile experience! The app worked perfectly on my tablet.');
$feedbackParams->setMeetingID('mobile-presentation-123');
$feedbackParams->setUserID('mobile-user-456');

$response = $bbb->feedback($feedbackParams);

if ($response->success()) {
    // Store feedback ID for follow-up
    $feedbackId = $response->getFeedbackID();
    
    // Could also store additional metadata locally
    $feedbackData = [
        'feedback_id' => $feedbackId,
        'session_token' => $response->getSessionToken(),
        'rating' => $response->getRating(),
        'comment' => $response->getComment(),
        'submitted_at' => $response->getSubmittedAt()
    ];
    
    echo "Mobile feedback submitted with ID: " . $feedbackId;
}
```

### Post-Meeting Feedback Collection

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\FeedbackParameters;

function collectMeetingFeedback($sessionToken, $meetingId, $userId) {
    $bbb = new BigBlueButton();
    
    // Create feedback parameters
    $feedbackParams = new FeedbackParameters($sessionToken);
    $feedbackParams->setMeetingID($meetingId);
    $feedbackParams->setUserID($userId);
    
    // In a real application, you would collect rating and comment from user input
    $rating = $_POST['rating'] ?? null; // 1-5 star rating
    $comment = $_POST['comment'] ?? ''; // User comments
    
    if ($rating !== null) {
        $feedbackParams->setRating((int)$rating);
    }
    
    if (!empty($comment)) {
        $feedbackParams->setComment($comment);
    }
    
    // Submit feedback
    $response = $bbb->feedback($feedbackParams);
    
    return $response;
}

// Usage after meeting ends
$sessionToken = 'user-session-from-meeting';
$meetingId = 'meeting-that-just-ended';
$userId = 'user-who-attended';

$feedbackResponse = collectMeetingFeedback($sessionToken, $meetingId, $userId);

if ($feedbackResponse->success()) {
    echo "Thank you for your feedback!";
} else {
    echo "Unable to submit feedback: " . $feedbackResponse->getMessage();
}
```

### Batch Feedback Collection

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\FeedbackParameters;

$bbb = new BigBlueButton();

// Collect feedback from multiple users
$sessionTokens = [
    'user1-session-token',
    'user2-session-token',
    'user3-session-token'
];

$feedbackResults = [];

foreach ($sessionTokens as $sessionToken) {
    $feedbackParams = new FeedbackParameters($sessionToken);
    $feedbackParams->setRating(rand(3, 5)); // Simulate different ratings
    $feedbackParams->setComment('Automated feedback collection');
    $feedbackParams->setMeetingID('batch-feedback-meeting');
    
    $response = $bbb->feedback($feedbackParams);
    $feedbackResults[] = [
        'session_token' => $sessionToken,
        'success' => $response->success(),
        'feedback_id' => $response->success() ? $response->getFeedbackID() : null,
        'message' => $response->getMessage()
    ];
}

// Process results
$successful = array_filter($feedbackResults, fn($r) => $r['success']);
echo "Successfully submitted " . count($successful) . " out of " . count($feedbackResults) . " feedback entries.";
```

## Response Format

The feedback API returns responses in JSON format with `application/json` content type.

### Successful Response Example

```json
{
  "status": "ok",
  "feedback_id": "feedback-123456",
  "session_token": "session-token-789",
  "meeting_id": "meeting456",
  "user_id": "user123",
  "rating": 4,
  "comment": "Great meeting experience!",
  "submitted_at": "2023-01-15T14:30:00Z",
  "processed": true,
  "feedback_type": "meeting_feedback",
  "additional_data": {
    "device-type": "mobile",
    "platform": "iOS"
  }
}
```

### Minimal Response Example

```json
{
  "status": "ok",
  "feedback_id": "feedback-minimal-789",
  "session_token": "minimal-session-token"
}
```

### Error Response Example

```json
{
  "status": "error",
  "message": "Invalid session token",
  "statuscode": "404"
}
```

## Response Fields

The FeedbackResponse provides the following fields:

### Required Fields

| Field | Type | Description |
|-------|------|-------------|
| `feedback_id` | String | Unique identifier for the feedback submission |
| `session_token` | String | The session token used for the feedback submission |

### Optional Fields

| Field | Type | Description |
|-------|------|-------------|
| `meeting_id` | String | Meeting ID if provided in the request |
| `user_id` | String | User ID if provided in the request |
| `rating` | Integer | Rating value if provided in the request |
| `comment` | String | Comment text if provided in the request |
| `submitted_at` | String | Timestamp when the feedback was submitted |
| `processed` | Boolean | Whether the feedback has been processed |
| `feedback_type` | String | Type/category of feedback |
| `additional_data` | Array | Additional metadata about the feedback |

## Response Handling

```php
$response = $bbb->feedback($feedbackParams);

if ($response->success()) {
    // Basic information
    echo "Feedback ID: " . $response->getFeedbackID();
    echo "Session Token: " . $response->getSessionToken();
    
    // Optional information
    if ($response->getMeetingID()) {
        echo "Meeting ID: " . $response->getMeetingID();
    }
    
    if ($response->getUserID()) {
        echo "User ID: " . $response->getUserID();
    }
    
    if ($response->getRating()) {
        echo "Rating: " . $response->getRating();
    }
    
    if ($response->getComment()) {
        echo "Comment: " . $response->getComment();
    }
    
    if ($response->getSubmittedAt()) {
        echo "Submitted At: " . $response->getSubmittedAt();
    }
    
    echo "Processed: " . ($response->getProcessed() ? 'Yes' : 'No');
    
    // Additional data handling
    $additionalData = $response->getAdditionalData();
    foreach ($additionalData as $key => $value) {
        echo "Additional Data - {$key}: {$value}";
    }
    
} else {
    echo "Error: " . $response->getMessage();
    echo "Status Code: " . $response->getStatusCode();
}
```

## Rating Guidelines

When implementing rating collection, consider these standard practices:

### 5-Star Rating Scale

```php
function getRatingLabel($rating) {
    $labels = [
        1 => 'Very Poor',
        2 => 'Poor', 
        3 => 'Average',
        4 => 'Good',
        5 => 'Excellent'
    ];
    
    return $labels[$rating] ?? 'Not Rated';
}

// Usage
$rating = 4;
echo "User rated the meeting: " . getRatingLabel($rating);
```

### Rating Validation

```php
function validateRating($rating) {
    // Rating should be between 1 and 5
    if ($rating !== null && ($rating < 1 || $rating > 5)) {
        throw new InvalidArgumentException('Rating must be between 1 and 5');
    }
    
    return $rating;
}

// Usage
try {
    $rating = validateRating($_POST['rating']);
    $feedbackParams->setRating($rating);
} catch (InvalidArgumentException $e) {
    echo "Invalid rating: " . $e->getMessage();
}
```

## Comment Guidelines

### Comment Length Validation

```php
function validateComment($comment) {
    $maxLength = 1000; // Maximum comment length
    
    if (strlen($comment) > $maxLength) {
        throw new InvalidArgumentException("Comment must be less than {$maxLength} characters");
    }
    
    // Remove excessive whitespace
    $comment = preg_replace('/\s+/', ' ', trim($comment));
    
    return $comment;
}

// Usage
try {
    $comment = validateComment($_POST['comment'] ?? '');
    if (!empty($comment)) {
        $feedbackParams->setComment($comment);
    }
} catch (InvalidArgumentException $e) {
    echo "Invalid comment: " . $e->getMessage();
}
```

### Comment Sanitization

```php
function sanitizeComment($comment) {
    // Basic sanitization - remove HTML tags and special characters
    $comment = strip_tags($comment);
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    
    return $comment;
}

// Usage
$rawComment = $_POST['comment'] ?? '';
$cleanComment = sanitizeComment($rawComment);
$feedbackParams->setComment($cleanComment);
```

## Error Handling

Common error scenarios and their handling:

```php
$response = $bbb->feedback($feedbackParams);

if (!$response->success()) {
    $message = $response->getMessage();
    $statusCode = $response->getStatusCode();
    
    switch ($statusCode) {
        case '400':
            // Bad request - invalid parameters
            echo "Invalid feedback parameters provided";
            break;
            
        case '401':
            // Unauthorized - invalid checksum or secret
            echo "Authentication failed";
            break;
            
        case '404':
            // Not found - invalid session token
            echo "Invalid or expired session token";
            break;
            
        case '429':
            // Too many requests - rate limiting
            echo "Too many feedback submissions. Please try again later.";
            break;
            
        case '500':
            // Internal server error
            echo "Server error occurred. Please try again later.";
            break;
            
        default:
            echo "Unknown error: " . $message;
    }
}
```

## Best Practices

### 1. **Timing**
- Collect feedback immediately after the meeting ends
- Consider follow-up emails for longer feedback collection periods

### 2. **User Experience**
- Keep the feedback form simple and quick to complete
- Use clear rating scales and descriptive labels
- Provide optional comment fields for detailed feedback

### 3. **Data Privacy**
- Inform users about how their feedback will be used
- Consider anonymizing feedback data for analysis
- Follow data protection regulations (GDPR, CCPA, etc.)

### 4. **Rate Limiting**
- Implement client-side rate limiting to prevent spam
- Consider server-side validation for multiple submissions

### 5. **Error Handling**
- Provide clear error messages to users
- Implement retry logic for temporary failures
- Log feedback submission failures for debugging

## Integration Examples

### Web Application Integration

```php
// In your meeting end controller
public function submitFeedback(Request $request) {
    try {
        $sessionToken = $request->input('session_token');
        $rating = $request->input('rating');
        $comment = $request->input('comment');
        
        $feedbackParams = new FeedbackParameters($sessionToken);
        
        if ($rating) {
            $feedbackParams->setRating((int)$rating);
        }
        
        if ($comment) {
            $feedbackParams->setComment($comment);
        }
        
        $bbb = new BigBlueButton();
        $response = $bbb->feedback($feedbackParams);
        
        if ($response->success()) {
            return response()->json([
                'success' => true,
                'feedback_id' => $response->getFeedbackID(),
                'message' => 'Thank you for your feedback!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->getMessage()
            ], 400);
        }
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while submitting feedback'
        ], 500);
    }
}
```

### JavaScript/AJAX Integration

```javascript
// Frontend feedback submission
function submitFeedback(sessionToken, rating, comment) {
    const data = {
        sessionToken: sessionToken,
        rating: rating,
        comment: comment
    };
    
    fetch('/api/feedback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for your feedback!');
            console.log('Feedback ID:', data.feedback_id);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error submitting feedback:', error);
        alert('An error occurred while submitting feedback');
    });
}

// Usage
submitFeedback('user-session-token', 4, 'Great meeting experience!');
```

## Analytics and Reporting

### Feedback Summary

```php
function generateFeedbackSummary($feedbackResponses) {
    $totalFeedback = count($feedbackResponses);
    $averageRating = 0;
    $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    
    foreach ($feedbackResponses as $response) {
        if ($response->getRating()) {
            $rating = $response->getRating();
            $averageRating += $rating;
            $ratingDistribution[$rating]++;
        }
    }
    
    if ($totalFeedback > 0) {
        $averageRating = $averageRating / $totalFeedback;
    }
    
    return [
        'total_feedback' => $totalFeedback,
        'average_rating' => round($averageRating, 2),
        'rating_distribution' => $ratingDistribution
    ];
}
```

This feedback API provides a standardized way to collect user feedback, enabling continuous improvement of the BigBlueButton platform through structured user input.

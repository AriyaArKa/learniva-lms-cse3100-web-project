# Gemini AI Chat Integration

## Feature Overview

Added Gemini AI integration to the chat system. Users can now trigger AI responses by starting their message with `@gpt`.

## What Was Implemented

### 1. **GeminiService** (`app/Services/GeminiService.php`)

-   Created a service to handle all Gemini AI API interactions
-   Methods:
    -   `generateResponse($prompt)`: Sends prompt to Gemini AI and returns response
    -   `isAiMessage($message)`: Checks if message starts with @gpt
    -   `extractPrompt($message)`: Removes @gpt prefix from message

### 2. **Updated ChatController** (`app/Http/Controllers/Backend/ChatController.php`)

-   Modified `SendMessage()` method to:
    -   Detect messages starting with @gpt
    -   Extract the actual prompt
    -   Generate AI response using GeminiService
    -   Save AI response as a message from the recipient
    -   Return both user message and AI response to frontend

### 3. **Updated ChatMessage.vue Component**

-   Modified `sendMsg()` method to handle AI responses
-   Added visual indicator (robot icon) when user types @gpt
-   Added computed property `isGptMessage` to detect @gpt messages
-   Updated placeholder text to guide users
-   Added pulsing animation for AI indicator icon

### 4. **Environment Configuration**

-   Added `GEMINI_API_KEY` to `.env` file
-   API Key: `AIzaSyBwlJ7lb7IrJQTS9ADwYrocUtGyKLsuPaM`

## How It Works

### User Flow:

1. User types a message starting with `@gpt` (e.g., "@gpt what is Laravel?")
2. Robot icon appears in the input field (with pulsing animation)
3. User sends the message
4. Backend:
    - Saves user's message to database
    - Detects @gpt prefix
    - Extracts prompt ("what is Laravel?")
    - Sends to Gemini API
    - Receives AI response
    - Saves AI response as message from recipient with "🤖 Gemini AI: " prefix
    - Broadcasts both messages
5. Frontend displays both messages in the chat

### Visual Indicators:

-   **Input Field**: Shows robot icon (🤖) when typing @gpt
-   **AI Response**: Prefixed with "🤖 Gemini AI: " in the chat

## Example Usage

**User sends:**

```
@gpt What is Laravel and what is it used for?
```

**AI responds:**

```
🤖 Gemini AI: Laravel is a free, open-source PHP web framework...
```

## Files Modified

1. ✅ `app/Services/GeminiService.php` (NEW)
2. ✅ `app/Http/Controllers/Backend/ChatController.php`
3. ✅ `resources/js/components/ChatMessage.vue`
4. ✅ `.env`

## Testing the Feature

### To Test:

1. Clear cache: `php artisan config:clear`
2. Start server: `php artisan serve`
3. Open chat in two different browsers/users
4. In one chat, type: `@gpt Hello, how are you?`
5. Press Enter or click Send
6. Both users should see:
    - Your message: "@gpt Hello, how are you?"
    - AI response: "🤖 Gemini AI: [Gemini's response]"

### Test Cases:

-   ✅ Regular message (without @gpt) - works normally
-   ✅ Message starting with @gpt - triggers AI response
-   ✅ Case insensitive (@GPT, @Gpt, @gpt) - all work
-   ✅ Both sender and recipient see AI response
-   ✅ AI response is attributed to the recipient

## API Configuration

### Gemini API Details:

-   **Endpoint**: `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent`
-   **Model**: gemini-2.0-flash-exp (Gemini 2.0 Experimental)
-   **API Version**: v1beta
-   **Authentication**: API Key via query parameter
-   **Timeout**: 30 seconds
-   **Content Type**: application/json

## Error Handling

The system handles:

-   Network failures
-   API errors
-   Timeout errors
-   Invalid responses

All errors are logged to Laravel logs and user sees a friendly error message.

## Future Enhancements

Potential improvements:

1. Add conversation context (remember previous messages)
2. Different AI personas (professional, casual, etc.)
3. Typing indicator while AI is generating response
4. Ability to regenerate AI response
5. Support for images/files with @gpt
6. Rate limiting to prevent API abuse
7. Custom AI commands (@gpt-translate, @gpt-summarize, etc.)

## Notes

-   AI responses are stored as messages from the recipient
-   Both users in the chat see the AI response
-   The @gpt prefix is kept in the saved message for context
-   AI responses are prefixed with "🤖 Gemini AI: " for easy identification

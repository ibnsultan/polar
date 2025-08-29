<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;


/**
 * Base Controller class for the application.
 * 
 * This class extends the Laravel BaseController and provides additional
 * functionality for handling common tasks such as rendering views, 
 * returning JSON responses, and managing error handling.
 * 
 * @package App\Http\Controllers
 * @author Abdulbasit Rubeya
 */

class Controller extends BaseController
{
    protected array $data = [];

    public function __construct()
    {

    }

    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        property_exists($this, $name) ? 
            $data = $this->$name :
            $data = $this->data[$name] ?? null;

        return $data;
    }

    /**
     * Generate an error page response.
     *
     * This method returns a response object that renders an error page
     * view based on the provided HTTP status code. The view file should
     * be located in the "errors" directory and named according to the
     * status code (e.g., "404.blade.php" for a 404 error).
     *
     * @param int $code The HTTP status code for the error page.
     * @return \Illuminate\Http\Response The response object containing the error page view.
     */
    protected function errorPage($code)
    {
        return response()->view(
            "errors.$code", 
            $this->data, 
            $code
        );
    }

    /**
     * Sends a JSON response indicating an error.
     *
     * This method sets the status to false and assigns the provided
     * error message to the response data. It then returns a JSON
     * response containing the data.
     *
     * @param string $message The error message to include in the response.
     * @param int $code The HTTP status code for the error response (default is 500).
     * @return \Illuminate\Http\JsonResponse The JSON response with the error data.
     */
    protected function jsonError($message, $code = 500)
    {
        $this->status = false;
        $this->message = $message;
        return response()->json($this->data, $code);
    }

    /**
     * Sends a JSON response indicating a successful operation.
     *
     * @param string $message The success message to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the success status and message.
     */
    protected function jsonSuccess($message)
    {
        $this->status = true;
        $this->message = $message;
        return response()->json($this->data);
    }

    /**
     * Handles exceptions and returns a JSON response.
     *
     * This method sets a default error message and status, and optionally includes
     * debugging information (exception message, line, and file) if the application
     * is in debug mode.
     *
     * @param \Exception $e The exception to handle.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the error details.
     */
    protected function jsonException($e)
    {
        $this->status = false;
        $this->message = "An unexpected error occurred";

        if (config('app.debug')) {
            $this->debug = [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }

        return response()->json($this->data);
    }

    /**
     * Database exception evaluator to return a user-friendly error message.
     *
     * This method evaluates database-related exceptions and provides
     * user-friendly error messages based on the SQLSTATE error codes.
     *
     * @param \Illuminate\Database\QueryException $e The database exception to evaluate.
     * @return string A user-friendly error message.
     */
    protected function dbExceptionEvaluator($e)
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $message = $e->errorInfo[2] ?? null;

        if(!$message) {
            switch ($sqlState) {
                case '23000': // Integrity constraint violation
                    $message = 'This content violates a database constraint.'; break;
                case '22001': // String data, right truncation
                    $message = 'The data you are trying to save is too long.'; break;
                case '42000': // Syntax error or access violation
                    $message = 'There is an issue with the database query syntax or permissions.'; break;
                case 'HY000': // General error
                    $message = 'A general database error occurred. Please try again later.'; break;
                case '42S02': // Table or view not found
                    $message = 'The requested table or view does not exist in the database.'; break;
                case '42S22': // Column not found
                    $message = 'A required column is missing in the database.'; break;
                case '23001': // Restrict violation
                    $message = 'This operation violates a database restriction.'; break;
                default:
                    $message = 'An unexpected database error occurred. Please contact support.';
            }
        }

        if(config('app.debug')) {
            $this->exception = [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }

        $this->status = false;
        $this->message = $message;

        return response()->json($this->data, 500);
    }

    /**
     * Try-catch Shorthand block for handling exceptions.
     * 
     * This method executes the provided callback function and catches any exceptions
     * that occur during its execution. If an exception is caught, it calls the
     * jsonException method to handle the error and return a JSON response.
     * 
     * @param callable $callback The callback function to execute.
     * @return mixed The result of the callback function if successful, or a JSON response if an exception occurs.
     */
    protected function tryCatch(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }
}

<?

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\FormInputController;

Route::get('/layanan/{id}/fields', [FormInputController::class, 'getFields']);
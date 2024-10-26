<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function updateAppointment(Request $request, $appointmentId) {
        try {
            // Localiza o agendamento pelo ID
            $appointment = Appointment::findOrFail($appointmentId);
    
            // Atualiza os campos permitidos (você pode adicionar ou remover campos conforme necessário)
            $appointment->date = Carbon::parse($request->input('date'))->format('Y-m-d H:i:s');
            $appointment->duration = $request->input('duration');
            $appointment->doctor_id = $request->input('doctor_id');
    
            // Salva as alterações
            $appointment->save();
    
            return response()->json(['message' => 'Agendamento atualizado com sucesso']);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar agendamento: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar agendamento'], 500);
        }
    }
    public function getUserAppointments($userId){
        try {
            $appointments = Appointment::where('user_id', $userId)->with('doctor')->get()->map(function ($appointment) {
                $appointment->date = Carbon::parse($appointment->date)->format('d/m/Y \à\s H:i');
                return $appointment;
            });;
            return response()->json(['appointments' => $appointments]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar agendamentos: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar agendamentos'], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);
        $appointment = Appointment::create([
            'user_id' => Auth::id(), 
            'doctor_id' => $validated['doctor_id'],
            'date' => $validated['date'],
            'duration' => $validated['duration'],
            'status' => 'pending'
        ]);
        return response()->json([
            'message' => 'Agendamento criado com sucesso!',
            'appointment' => $appointment
        ], 201);
    }

    public function deleteAppointment($appointmentId) {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            $appointment->delete();
    
            return response()->json(['message' => 'Agendamento deletado com sucesso']);
        } catch (\Exception $e) {
            \Log::error('Erro ao deletar agendamento: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao deletar agendamento'], 500);
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncChatSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:sync-sessions';
    protected $description = 'Sincroniza mensajes existentes con la nueva tabla de sesiones';

    public function handle()
    {
        $sessions = \App\Models\Message::select('session_id', 'user_id')
            ->groupBy('session_id', 'user_id')
            ->get();

        $count = 0;
        foreach ($sessions as $session) {
            $exists = \App\Models\ChatSession::where('session_id', $session->session_id)->exists();
            if (!$exists) {
                // Buscar el primer mensaje para el título
                $firstMsg = \App\Models\Message::where('session_id', $session->session_id)
                    ->where('role', 'user')
                    ->orderBy('created_at', 'asc')
                    ->first();
                
                $content = $firstMsg ? ($firstMsg->content ?? $firstMsg->message ?? $firstMsg->text) : null;
                $title = $content 
                    ? mb_strtoupper(mb_substr($content, 0, 35)) . '...'
                    : 'CHAT ANTIGUO';

                \App\Models\ChatSession::create([
                    'session_id' => $session->session_id,
                    'user_id' => $session->user_id,
                    'title' => $title,
                    'is_archived' => false
                ]);
                $count++;
            }
        }

        $this->info("Sincronizadas $count sesiones.");
    }
}

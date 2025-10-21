<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeStatutChange extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Commande $commande,
        public string $ancienStatut
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->getMessageStatut();
        
        return (new MailMessage)
            ->subject("Mise à jour de votre commande #{$this->commande->numero_commande}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line($message)
            ->line("Numéro de commande: {$this->commande->numero_commande}")
            ->line("Montant: " . number_format($this->commande->montant_total, 0, ',', ' ') . " FCFA")
            ->action('Voir ma commande', url('/client/commandes/' . $this->commande->id))
            ->line('Merci de votre confiance !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'commande_id' => $this->commande->id,
            'numero_commande' => $this->commande->numero_commande,
            'ancien_statut' => $this->ancienStatut,
            'nouveau_statut' => $this->commande->statut,
            'message' => $this->getMessageStatut(),
        ];
    }

    private function getMessageStatut(): string
    {
        return match($this->commande->statut) {
            'en_attente' => 'Votre commande a été reçue et est en attente de traitement.',
            'en_preparation' => '🍳 Votre commande est en cours de préparation par nos cuisiniers !',
            'prete' => '✅ Bonne nouvelle ! Votre commande est prête.',
            'en_livraison' => '🚚 Votre commande est en route vers vous !',
            'livree' => '🎉 Votre commande a été livrée. Bon appétit !',
            'annulee' => '❌ Votre commande a été annulée. Contactez-nous pour plus d\'informations.',
            default => 'Le statut de votre commande a été mis à jour.',
        };
    }
}
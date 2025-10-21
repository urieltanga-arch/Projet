// Système de mise à jour en temps réel pour le dashboard employé

class EmployeeDashboardRealtime {
    constructor() {
        this.updateInterval = 15000; // 15 secondes
        this.lastUpdate = Date.now();
        this.notificationSound = new Audio('/sounds/notification.mp3');
    }

    /**
     * Initialise le système de mise à jour
     */
    init() {
        this.startPolling();
        this.initNotifications();
    }

    /**
     * Démarre le polling régulier
     */
    startPolling() {
        setInterval(() => {
            this.updateStatistiques();
            this.checkNouvellesCommandes();
        }, this.updateInterval);
    }

    /**
     * Met à jour les statistiques
     */
    async updateStatistiques() {
        try {
            const response = await fetch('/api/employee/statistiques', {
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Erreur réseau');

            const data = await response.json();
            this.updateStatsUI(data);
        } catch (error) {
            console.error('Erreur lors de la mise à jour des stats:', error);
        }
    }

    /**
     * Vérifie les nouvelles commandes
     */
    async checkNouvellesCommandes() {
        try {
            const timestamp = Math.floor(this.lastUpdate / 1000);
            const response = await fetch(`/api/employee/nouvelles-commandes/${timestamp}`, {
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Erreur réseau');

            const data = await response.json();
            
            if (data.count > 0) {
                this.handleNouvellesCommandes(data.commandes);
                this.lastUpdate = Date.now();
            }
        } catch (error) {
            console.error('Erreur lors de la vérification des nouvelles commandes:', error);
        }
    }

    /**
     * Gère l'affichage des nouvelles commandes
     */
    handleNouvellesCommandes(commandes) {
        // Jouer le son de notification
        this.playNotificationSound();

        // Afficher une notification navigateur
        this.showBrowserNotification(commandes.length);

        // Afficher une alerte visuelle
        this.showVisualAlert(commandes.length);

        // Mettre à jour l'interface
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }

    /**
     * Met à jour l'interface avec les nouvelles stats
     */
    updateStatsUI(data) {
        // Commandes en attente
        const attenteEl = document.querySelector('[data-stat="commandes_attente"]');
        if (attenteEl) {
            this.animateNumber(attenteEl, parseInt(attenteEl.textContent), data.commandes_en_attente);
        }

        // Commandes aujourd'hui
        const jourEl = document.querySelector('[data-stat="commandes_jour"]');
        if (jourEl) {
            this.animateNumber(jourEl, parseInt(jourEl.textContent), data.commandes_aujourdhui);
        }

        // Réclamations
        const reclamEl = document.querySelector('[data-stat="reclamations"]');
        if (reclamEl) {
            this.animateNumber(reclamEl, parseInt(reclamEl.textContent), data.reclamations_non_traitees);
        }

        // Revenu
        const revenuEl = document.querySelector('[data-stat="revenu"]');
        if (revenuEl) {
            const newValue = Math.floor(data.revenu_jour / 1000);
            this.animateNumber(revenuEl, parseInt(revenuEl.textContent.replace('K', '')), newValue, 'K');
        }
    }

    /**
     * Anime le changement de nombre
     */
    animateNumber(element, start, end, suffix = '') {
        const duration = 500;
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current) + suffix;
        }, 16);
    }

    /**
     * Joue le son de notification
     */
    playNotificationSound() {
        this.notificationSound.play().catch(err => {
            console.log('Impossible de jouer le son:', err);
        });
    }

    /**
     * Affiche une notification navigateur
     */
    showBrowserNotification(count) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Nouvelles commandes !', {
                body: `${count} nouvelle${count > 1 ? 's' : ''} commande${count > 1 ? 's' : ''} reçue${count > 1 ? 's' : ''}`,
                icon: '/images/logo.png',
                badge: '/images/badge.png'
            });
        }
    }

    /**
     * Affiche une alerte visuelle
     */
    showVisualAlert(count) {
        const alert = document.createElement('div');
        alert.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-bounce';
        alert.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-3xl">🔔</span>
                <div>
                    <div class="font-bold text-lg">Nouvelles commandes !</div>
                    <div>${count} commande${count > 1 ? 's' : ''} reçue${count > 1 ? 's' : ''}</div>
                </div>
            </div>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }

    /**
     * Initialise les notifications navigateur
     */
    initNotifications() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    /**
     * Récupère le token d'authentification
     */
    getToken() {
        // Vous devrez implémenter ceci selon votre système d'auth
        return document.querySelector('meta[name="api-token"]')?.content || '';
    }
}

// Initialiser le système au chargement de la page
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const dashboard = new EmployeeDashboardRealtime();
        dashboard.init();
    });
} else {
    const dashboard = new EmployeeDashboardRealtime();
    dashboard.init();
}
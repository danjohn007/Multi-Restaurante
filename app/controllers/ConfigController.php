<?php
require_once __DIR__ . '/Controller.php';

class ConfigController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth(['admin', 'superadmin']);
    }
    
    public function index() {
        $settings = $this->getSystemSettings();
        
        $data = [
            'title' => 'Configuración del Sistema',
            'settings' => $settings
        ];
        
        $this->loadView('layout/header', $data);
        $this->loadView('configuracion/index', $data);
        $this->loadView('layout/footer');
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        try {
            $settingsModel = $this->loadModel('Setting');
            
            // Process all form data
            $allowedSettings = [
                'app_name', 'app_description', 'app_logo_url', 'meta_title', 
                'meta_description', 'meta_keywords', 'default_opening_time',
                'default_closing_time', 'contact_email', 'support_email',
                'contact_phone', 'whatsapp_number', 'contact_address',
                'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
                'notification_email_enabled', 'notification_sms_enabled',
                'maintenance_mode', 'maintenance_message'
            ];
            
            foreach ($allowedSettings as $setting) {
                if (isset($_POST[$setting])) {
                    $settingsModel->setSetting($setting, $_POST[$setting]);
                }
            }
            
            // Handle operating days as array
            if (isset($_POST['operating_days'])) {
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($days as $day) {
                    $isEnabled = in_array($day, $_POST['operating_days']) ? '1' : '0';
                    $settingsModel->setSetting("operating_days_{$day}", $isEnabled);
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Configuraciones guardadas exitosamente'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error al guardar configuraciones: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getSystemSettings() {
        try {
            $settingsModel = $this->loadModel('Setting');
            return $settingsModel->getAllSettings();
        } catch (Exception $e) {
            // Return default settings if Settings model doesn't exist
            return [
                'app_name' => APP_NAME ?? 'Multi-Restaurante',
                'app_description' => 'Sistema de gestión multi-restaurante',
                'meta_title' => 'Multi-Restaurante - Sistema de Reservaciones',
                'meta_description' => 'Sistema completo de gestión de reservaciones para múltiples restaurantes',
                'meta_keywords' => 'restaurante, reservaciones, mesas, comida, gastronomía, sistema, gestión, horarios',
                'default_opening_time' => '08:00',
                'default_closing_time' => '22:00',
                'contact_email' => 'contacto@multirestaurante.com',
                'support_email' => 'soporte@multirestaurante.com',
                'contact_phone' => '+52 55 1234 5678',
                'maintenance_mode' => '0'
            ];
        }
    }
}
?>
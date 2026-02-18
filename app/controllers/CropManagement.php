<?php
class CropManagement extends Controller
{
    private $fieldModel;
    private $cycleModel;
    private $inventoryModel;
    private $logModel;
    private $financeModel;
    private $cropModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'farmer') {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->fieldModel = $this->model('Field');
        $this->cycleModel = $this->model('CropCycle');
        $this->inventoryModel = $this->model('Inventory');
        $this->logModel = $this->model('FarmLog');
        $this->financeModel = $this->model('Finance');
        $this->cropModel = $this->model('Crop');
    }

    public function index()
    {
        // alerts logic
        $cycles = $this->cycleModel->getCycles($_SESSION['user_id']);
        $alerts = [];
        $today = new DateTime();

        foreach ($cycles as $cycle) {
            if ($cycle->status == 'active' && !empty($cycle->expected_harvest_date)) {
                $harvest_date = new DateTime($cycle->expected_harvest_date);
                $diff = $today->diff($harvest_date)->days;
                $invert = $today->diff($harvest_date)->invert; // 1 if past

                if (!$invert && $diff <= 14) {
                    $alerts[] = [
                        'type' => 'info',
                        'message' => "Harvest for {$cycle->crop_name} ({$cycle->field_name}) is expected in {$diff} days."
                    ];
                } elseif ($invert) {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => "Harvest for {$cycle->crop_name} ({$cycle->field_name}) was expected on {$cycle->expected_harvest_date}. Update status if harvested."
                    ];
                }
            }
        }

        $crops = $this->cropModel->getCrops();

        $data = [
            'title' => 'Crop Management',
            'description' => 'Manage your farm activities comprehensively.',
            'alerts' => $alerts,
            'crops' => $crops
        ];
        $this->view('farmer/crop_management/dashboard', $data);
    }

    // --- FIELDS ---
    public function fields()
    {
        $fields = $this->fieldModel->getFields($_SESSION['user_id']);
        $data = [
            'fields' => $fields
        ];
        $this->view('farmer/crop_management/fields', $data);
    }

    public function add_field()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'size' => trim($_POST['size']),
                'soil_type' => trim($_POST['soil_type']),
                'location' => trim($_POST['location'])
            ];
            if ($this->fieldModel->addField($data)) {
                header('location: ' . URLROOT . '/crop_management/fields');
            } else {
                die('Something went wrong');
            }
        }
    }

    // --- CROP PLANNING ---
    public function planning()
    {
        $cycles = $this->cycleModel->getCycles($_SESSION['user_id']);
        $crops = $this->cropModel->getCrops(); // For dropdown
        $fields = $this->fieldModel->getFields($_SESSION['user_id']); // For dropdown

        $data = [
            'cycles' => $cycles,
            'crops' => $crops,
            'fields' => $fields
        ];
        $this->view('farmer/crop_management/planning', $data);
    }

    public function add_cycle()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'crop_id' => trim($_POST['crop_id']),
                'field_id' => trim($_POST['field_id']),
                'season' => trim($_POST['season']),
                'start_date' => trim($_POST['start_date']),
                'expected_harvest_date' => trim($_POST['expected_harvest_date']),
                'status' => 'planned'
            ];
            if ($this->cycleModel->addCycle($data)) {
                header('location: ' . URLROOT . '/crop_management/planning');
            } else {
                die('Something went wrong');
            }
        }
    }

    // --- INVENTORY ---
    public function inventory()
    {
        $seeds = $this->inventoryModel->getSeeds($_SESSION['user_id']);
        $fertilizers = $this->inventoryModel->getFertilizers($_SESSION['user_id']);
        $data = [
            'seeds' => $seeds,
            'fertilizers' => $fertilizers
        ];
        $this->view('farmer/crop_management/inventory', $data);
    }

    public function add_seed()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'variety' => trim($_POST['variety']),
                'quantity' => trim($_POST['quantity']),
                'unit' => trim($_POST['unit']),
                'purchase_date' => trim($_POST['purchase_date']),
                'expiry_date' => trim($_POST['expiry_date'])
            ];
            $this->inventoryModel->addSeed($data);
            header('location: ' . URLROOT . '/crop_management/inventory');
        }
    }

    public function add_fertilizer()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'quantity' => trim($_POST['quantity']),
                'unit' => trim($_POST['unit'])
            ];
            $this->inventoryModel->addFertilizer($data);
            header('location: ' . URLROOT . '/crop_management/inventory');
        }
    }

    // --- TRACKING/LOGS ---
    public function tracking($cycle_id = null)
    {
        // If no cycle selected, show list of active cycles to select
        if (!$cycle_id) {
            $cycles = $this->cycleModel->getCycles($_SESSION['user_id']); // Should filter by active
            $data = ['cycles' => $cycles, 'selected_cycle' => null];
            $this->view('farmer/crop_management/tracker', $data);
            return;
        }

        // Load logs for specific cycle
        $irrigation = $this->logModel->getIrrigationLogs($cycle_id);
        $nutrients = $this->logModel->getNutrientLogs($cycle_id);
        $pests = $this->logModel->getPestReports($cycle_id);
        $cycle = $this->cycleModel->getCycleById($cycle_id);

        $data = [
            'selected_cycle' => $cycle,
            'irrigation' => $irrigation,
            'nutrients' => $nutrients,
            'pests' => $pests,
            'cycles' => $this->cycleModel->getCycles($_SESSION['user_id']),
            'cycle_id' => $cycle_id,
            'fertilizers' => $this->inventoryModel->getFertilizers($_SESSION['user_id']) // For dropdown
        ];
        $this->view('farmer/crop_management/tracker', $data);
    }

    public function log_irrigation()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'cycle_id' => $_POST['cycle_id'],
                'date' => $_POST['date'],
                'duration' => $_POST['duration'],
                'method' => $_POST['method'],
                'water_volume' => $_POST['water_volume'],
                'notes' => $_POST['notes']
            ];
            $this->logModel->addIrrigation($data);
            header('location: ' . URLROOT . '/crop_management/tracking/' . $data['cycle_id']);
        }
    }

    public function log_nutrient()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'cycle_id' => $_POST['cycle_id'],
                'fertilizer_id' => $_POST['fertilizer_id'],
                'date' => $_POST['date'],
                'quantity_used' => $_POST['quantity_used'],
                'notes' => $_POST['notes']
            ];
            $this->logModel->addNutrient($data);
            header('location: ' . URLROOT . '/crop_management/tracking/' . $data['cycle_id']);
        }
    }

    public function log_pest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Image upload logic skipped for brevity, assumed simple path or skipped
            $data = [
                'cycle_id' => $_POST['cycle_id'],
                'pest_name' => $_POST['pest_name'],
                'severity' => $_POST['severity'],
                'observation_date' => $_POST['observation_date'],
                'image_path' => '',
                'notes' => $_POST['notes']
            ];
            $this->logModel->addPestReport($data);
            header('location: ' . URLROOT . '/crop_management/tracking/' . $data['cycle_id']);
        }
    }

    // --- FINANCIALS ---
    public function financials()
    {
        $expenses = $this->financeModel->getExpenses($_SESSION['user_id']);
        $total_revenue = $this->financeModel->getTotalRevenue($_SESSION['user_id']);
        $total_expenses = $this->financeModel->getTotalExpenses($_SESSION['user_id']);
        $profit = $total_revenue - $total_expenses;

        $cycles = $this->cycleModel->getCycles($_SESSION['user_id']); // For linking expense to cycle

        $data = [
            'expenses' => $expenses,
            'total_revenue' => $total_revenue,
            'total_expenses' => $total_expenses,
            'profit' => $profit,
            'cycles' => $cycles
        ];
        $this->view('farmer/crop_management/financials', $data);
    }

    public function add_expense()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'category' => $_POST['category'],
                'amount' => $_POST['amount'],
                'date' => $_POST['date'],
                'description' => $_POST['description'],
                'related_cycle_id' => !empty($_POST['related_cycle_id']) ? $_POST['related_cycle_id'] : null
            ];
            $this->financeModel->addExpense($data);
            header('location: ' . URLROOT . '/crop_management/financials');
        }
    }

    public function add_harvest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'cycle_id' => $_POST['cycle_id'],
                'date' => $_POST['date'],
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'market_price' => $_POST['market_price'],
                'notes' => $_POST['notes']
            ];
            $this->logModel->addHarvest($data);

            // Update cycle status if needed?

            header('location: ' . URLROOT . '/crop_management/financials');
        }
    }
}

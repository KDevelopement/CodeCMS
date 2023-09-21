<?php
    namespace App\Libraries;

    /**
     * Logs de atividades
     * @author K'Seven - https://kseven.dev.br/
     */

    use App\Models\ActivitiesModel;

    class Activities
    {
        /**
         * @var ActivitiesModel|null $Activitie
         */
        protected $Activitie;

        /**
         * @var bool
         */
        protected $Api;

        /**
         * @var bool
         */
        protected $Cron;

        /**
         * @var bool
         */
        protected $saveLogs;

        /**
         * @var null|User|false $User
         */
        protected $User;

        public function __construct()
        {
            helper(['setting']);
            $this->Activitie = model(ActivitiesModel::class);
            $this->Api = false;
            $this->Cron = false;
            $this->saveLogs = setting('Config.saveLogs');
            $this->User =  auth()->loggedIn() ? auth()->user() : false;
        }

        public function Api()
        {
            $this->Api = true;
            return $this;
        }

        public function Cron()
        {
            $this->Cron = true;
            return $this;
        }

        public function addlog(string $Function, array $Log, bool $ReturnID = false)
        { 
            if (!$this->saveLogs) {
                return;
            }
            $isAPI = $this->Api ? "1" : "0";
            $isCron = $this->Cron ? "1" : "0";
            $Date = [
                "api" => $isAPI,
                "cron" => $isCron,
                "function" => $Function,
                "details" => json_encode($Log),
            ];
            $isUser = ($this->Api OR $this->Cron) ? false : true;
            return $this->insert($Date, $ReturnID, $isUser);
        }

        private function insert(array $Date, ?bool $ReturnID = false, ?bool $UserID = false)
        {
            if ($UserID) {
                $Date = array_merge($Date, [
                    "created_user" => user_id(),
                ]);
            }
            return $this->Activitie->insert($Date, $ReturnID);
        }

        public function update(int $ID, array $Date)
        {
            return $this->Activitie->update($ID, $Date);
        }

        public function delete(int $ID)
        {
            return $this->Activitie->delete($ID);
        }

        public function recovery(int $ID)
        {
            $Date = [
                "deleted_at" => null,
            ];
            return $this->Activitie->update($ID, $Date);
        }

        //  Functions;

        public function getAlls()
        {
            $Result = [];
            $Activities = $this->Activitie->getAlls();
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return $Result;
        }

        public function getAllsWithPagination()
        {
            $Result = [];
            $PaginationConfig = [
                "perPage" => setting('Pager.perPage'),
                "Segment" => setting("Pager.Segment"),
                "Template" => setting("Pager.Template"),
                "Group" => setting("Pager.Group")
            ];
            $Result = [];
            $Activities = $this->Activitie->getAllsWithPagination()->paginate($PaginationConfig["perPage"], $PaginationConfig["Group"], null, $PaginationConfig["Segment"]);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                $L["ID"] = $Activitie->id;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return [
                "Result" => $Result,
                "Links" => $this->Activitie->pager->links($PaginationConfig["Group"], $PaginationConfig["Template"]),
            ];
        }

        public function getAllsWithPaginationUsers()
        {
            $Result = [];
            $PaginationConfig = [
                "perPage" => setting('Pager.perPage'),
                "Segment" => setting("Pager.Segment"),
                "Template" => setting("Pager.Template"),
                "Group" => setting("Pager.Group")
            ];
            $Result = [];
            $Activities = $this->Activitie->getAllsWithPaginationUsers()->paginate($PaginationConfig["perPage"], $PaginationConfig["Group"], null, $PaginationConfig["Segment"]);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                $L["ID"] = $Activitie->id;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return [
                "Result" => $Result,
                "Links" => $this->Activitie->pager->links($PaginationConfig["Group"], $PaginationConfig["Template"]),
            ];
        }

        public function getAllsWithPaginationWithUser($UserID)
        {
            $Result = [];
            $PaginationConfig = [
                "perPage" => setting('Pager.perPage'),
                "Segment" => setting("Pager.Segment"),
                "Template" => setting("Pager.Template"),
                "Group" => setting("Pager.Group")
            ];
            $Result = [];
            $Activities = $this->Activitie->getAllsWithPaginationWithUser($UserID)->paginate($PaginationConfig["perPage"], $PaginationConfig["Group"], null, $PaginationConfig["Segment"]);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                $L["ID"] = $Activitie->id;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return [
                "Result" => $Result,
                "Links" => $this->Activitie->pager->links($PaginationConfig["Group"], $PaginationConfig["Template"]),
            ];
        }

        public function getAllsWithPaginationForApi()
        {
            $Result = [];
            $PaginationConfig = [
                "perPage" => setting('Pager.perPage'),
                "Segment" => setting("Pager.Segment"),
                "Template" => setting("Pager.Template"),
                "Group" => setting("Pager.Group")
            ];
            $Result = [];
            $Activities = $this->Activitie->getAllsWithPaginationForApi()->paginate($PaginationConfig["perPage"], $PaginationConfig["Group"], null, $PaginationConfig["Segment"]);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                $L["ID"] = $Activitie->id;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return [
                "Result" => $Result,
                "Links" => $this->Activitie->pager->links($PaginationConfig["Group"], $PaginationConfig["Template"]),
            ];
        }

        public function getAllsWithPaginationForCron()
        {
            $Result = [];
            $PaginationConfig = [
                "perPage" => setting('Pager.perPage'),
                "Segment" => setting("Pager.Segment"),
                "Template" => setting("Pager.Template"),
                "Group" => setting("Pager.Group")
            ];
            $Result = [];
            $Activities = $this->Activitie->getAllsWithPaginationForCron()->paginate($PaginationConfig["perPage"], $PaginationConfig["Group"], null, $PaginationConfig["Segment"]);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                $L["ID"] = $Activitie->id;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return [
                "Result" => $Result,
                "Links" => $this->Activitie->pager->links($PaginationConfig["Group"], $PaginationConfig["Template"]),
            ];
        }

        public function getLastTime($Time = "24", $TypeTime = "HOUR", $Limit = 5)
        {
            $Result = [];
            $Activities = $this->Activitie->getLastTime($Time, $TypeTime, $Limit);
            $Count = 0;
            foreach ($Activities AS $Activitie) {
                $Count++;
                $L["Number"] = $Count;
                if(!empty($Activitie->details)) {
                    if($Activitie->api == 1) {
                        $Icon = "ni ni-world-2 text-white";
                    } else {
                        $Icon = "ni ni-single-02 text-white";
                    }
                    $Details = $Activitie->details;
                    $Details = json_decode($Details);
                    $Details = lang("Activities." . $Activitie->function, $Details);
                } else {
                    $Details = log("Activities.Error registering the activity log.");
                    $Icon = "ni ni-fat-remove text-danger";
                }
                $L["Icon"] = $Icon;
                $L["Details"] = $Details;
                $getDate = $Activitie->created_at;
                $DateTime = explode(" ", $getDate);
                $Date = new \DateTimeImmutable($DateTime[0]);
                $Date =  $Date->format('d/m/Y');
                $Date = explode("/", $Date);
                $Months = [
                    "01" => "Janeiro", 
                    "02" => "Fevereiro", 
                    "03" => "Março", 
                    "04" => "Abril", 
                    "05" => "Maio", 
                    "06" => "Junho", 
                    "07" => "Julho", 
                    "08" => "Agosto", 
                    "09" => "Setembro", 
                    "10" => "Outubro", 
                    "11" => "Novembro", 
                    "12" => "Dezembro"
                ];
                $Month = $Date[1];
                $Date = $Date[0] . " " . "de" . " " . $Months[$Month] . " " . "de" . " " . $Date[2];
                $Time = new \DateTimeImmutable($DateTime[1]);
                $Time =  $Time->format('H:i');
                $L["Date"] = $Date . " " . "ás" . " " . "<span class=\"font-weight-bold\">" . $Time . "</span>.";
                $Result[] = $L;
            }
            return $Result;
        }
        

    }
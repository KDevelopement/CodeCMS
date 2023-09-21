<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class ActivitiesModel extends Model
    {
        //protected $DBGroup          = 'default';

        protected $table            = 'activities_logs';
        protected $primaryKey       = 'id';

        protected $useAutoIncrement = true;

        protected $returnType       = 'object';
        protected $useSoftDeletes   = true;

        protected $protectFields    = true;
        protected $allowedFields    = [
            "api", 
            "cron", 
            "function", 
            "details", 
            "created_user",
            "deleted_at", 
        ];

        // Dates
        protected $useTimestamps = true;
        protected $dateFormat    = 'datetime';
        protected $createdField  = 'created_at';
        protected $updatedField  = 'updated_at';
        protected $deletedField  = 'deleted_at';

        // Validation
        protected $validationRules      = [];
        protected $validationRulesEdit  = [];
        protected $validationMessages   = [];
        protected $skipValidation       = false;
        protected $cleanValidationRules = true;

        // Callbacks
        protected $allowCallbacks = true;
        protected $beforeInsert   = [];
        protected $afterInsert    = [];
        protected $beforeUpdate   = [];
        protected $afterUpdate    = [];
        protected $beforeFind     = [];
        protected $afterFind      = [];
        protected $beforeDelete   = [];
        protected $afterDelete    = [];

        public function countAll()
        {
            $builder = $this->table($this->table);
            return $builder->countAllResults();
        }

        public function getAlls()
        {
            $builder = $this->table($this->table);
            $builder->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $builder->orderBy($this->table . '.created_at', 'DESC');
            return $builder->findAll();
        }

        public function getAllsWithPagination()
        {
            $this->table($this->table);
            $this->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $this->orderBy($this->table . '.created_at', 'DESC');
            return $this;
        }

        public function getAllsWithPaginationUsers()
        {
            $this->table($this->table);
            $this->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $this->where("api", "0");
            $this->where("cron", "0");
            $this->orderBy($this->table . '.created_at', 'DESC');
            return $this;
        }
        
        public function getAllsWithPaginationWithUser($UserID)
        {
            $this->table($this->table);
            $this->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $this->where("api", "0");
            $this->where("cron", "0");
            $this->where("created_user", $UserID);
            $this->orderBy($this->table . '.created_at', 'DESC');
            return $this;
        }

        public function getAllsWithPaginationForApi()
        {
            $this->table($this->table);
            $this->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $this->where("api", "1");
            $this->where("cron", "0");
            $this->orderBy($this->table . '.created_at', 'DESC');
            return $this;
        }

        public function getAllsWithPaginationForCron()
        {
            $this->table($this->table);
            $this->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $this->where("api", "0");
            $this->where("cron", "1");
            $this->orderBy($this->table . '.created_at', 'DESC');
            return $this;
        }

        public function getLastTime($Time, $TypeTime, $Limit = 10)
        {
            $builder = $this->table($this->table);
            $builder->select(
                $this->table . ".id, " .
                $this->table . ".api, " .
                $this->table . ".cron, " .
                $this->table . ".function, " .
                $this->table . ".details, " .
                $this->table . ".created_user, " .
                $this->table . ".created_at"
            );
            $builder->where($this->table . ".created_at >= DATE_SUB(NOW(), INTERVAL " . $Time . " " . $TypeTime . ")", NULL, FALSE);
            $builder->orderBy($this->table . '.created_at', 'DESC');
            $builder->limit($Limit);
            return $builder->find();
        }
        
        public function recovery($ID)
        {
            $builder = $this->table($this->table);
            $builder->where($this->table . ".id", $ID);
            $builder->where($this->table . ".deleted_at != " . null);
            return $builder->set(["deleted_at" => null]);
        }
    }

<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

/**
 * Summary of CrimeScenesModel
 */
class CrimeScenesModel extends BaseModel
{
    private $table_name = "crime_scenes";
    
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of handleGetAllCrimeScenes
     * @param array $filters
     * @return array
     */
    public function handleGetAllCrimeScenes(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM $this->table_name WHERE 1 ";
        
        //filters
        if(isset($filters["street"])){
            $sql .= " AND street LIKE CONCAT(:street,'%') ";
            $query_values[":street"] = $filters["street"]."%";
        }

        if(isset($filters["city"])){
            $sql .= " AND city LIKE CONCAT(:city,'%') ";
            $query_values[":city"] = $filters["city"]."%";
        }

        if(isset($filters["crime_sceneID"])){
            $sql .= " AND crime_sceneID LIKE CONCAT(:crime_sceneID,'%') ";
            $query_values[":crime_sceneID"] = $filters["crime_sceneID"]."%";
        }

        // sorting
        if(isset($filters["sort_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "street"){
                $sql .= " ORDER BY street";
            } elseif($sort_by == "city"){
                $sql .= " ORDER BY city";
            } elseif($sort_by == "crime_sceneID"){
                $sql .= " ORDER BY crime_sceneID";
            } 
        }

        return $this->paginate($sql, $query_values, 'crime_scenes');
    }

    /**
     * Summary of handleGetCrimeSceneById
     * @param mixed $crime_scene_id
     * @return mixed
     */
    public function handleGetCrimeSceneById($crime_scene_id) {
        $sql = "SELECT * FROM $this->table_name WHERE crime_sceneID = :crime_sceneID";
        $query_values = [":crime_sceneID" => $crime_scene_id];

        return $this->run($sql, $query_values)->fetchAll();
    }

    public function CrimeSceneExists($crime_sceneID) {
        $sql = "SELECT COUNT(*) FROM $this->table_name WHERE crime_sceneID = :crime_sceneID";
        $query_values = [":crime_sceneID" => $crime_sceneID];
    
        $result = $this->run($sql, $query_values)->fetchColumn();
    
        return ($result > 0);
    }

     /**
     * Inserts a crime scene in the database
     * @param $crime_scene
     */
    public function createCrimeScene($crime_scene) {
        return $this->insert('crime_scenes', $crime_scene);
    }

    /**
     * Summary of updateCrimeScene
     * @param mixed $crime_scene
     * @return void
     */
     /**
     * Summary of updateCrime_Scenes
     * @param mixed $crime_scenes
     * @return void
     */
    public function updateCrime_Scenes($crime_scenes)
    {
        foreach ($crime_scenes as $crime_scene) {
            if (!is_array($crime_scene) || !array_key_exists('crime_sceneID', $crime_scene)) {
                continue;
            }
            $crime_sceneID = $crime_scene['crime_sceneID'];
            unset($crime_scene['crime_sceneID']);
            $this->update('crime_scenes', $crime_scene, ['crime_sceneID' => $crime_sceneID]);
        }
    }

    /**
     * Summary of deleteCrimeScene
     * @param mixed $crime_sceneID
     * @return void
     */
    public function deleteJudge($crime_sceneID)
    {
        $where = ['crime_sceneID' => $crime_sceneID];
        $deletedCount = $this->delete($this->table_name, $where);
        return $deletedCount;
    }
}

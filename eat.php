<?
class eat{
   public $token, $error,$exclude;
   private $private_key, $algo;

	public function __construct($private_key,$algo="md5") {
      $this->private_key = $private_key;
      $this->algo = $algo;
   }

   public function exclusions($exclude){
      $this->exclude = $exclude;
   }

   public function create($uid,$exp_date){
      if(strtotime($exp_date)<time()){
         $this->error = "exp_date must be in the future";
         return false;
      }
      $string = $uid.$exp_date.$this->private_key;
      $hash = hash($this->algo,$string);
      $this->token = $uid."|".$exp_date."|".$hash;
      return $this->token;
   }

   public function check($token){
      list($uid,$exp_date,$hash) = explode("|",$token);
      $string = $uid.$exp_date.$this->private_key;
      $test_hash = hash($this->algo,$string);

      if(in_array($uid,$this->exclude)){
         $this->error = "Excluded user";
         return false;
      }

      if($test_hash!=$hash){
         $this->error = "Hash doesn't match";
         return false;
      }

      if(strtotime($exp_date)<time()){
         $this->error = "expired";
         return false;
      }
      return true;
   }
}

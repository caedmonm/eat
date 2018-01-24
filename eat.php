<?
class eat{
   public $token;
   private $private_key, $algo;

	public function __construct($private_key,$algo="md5") {
      $this->private_key = $private_key;
      $this->algo = $algo;
   }

   public function create($uid,$exp_date){
      if(strtotime($exp_date)<time()){
         return "exp_date must be in the future";
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

      if($test_hash!=$hash){
         return "Hash doesn't match";
      }

      if(strtotime($exp_date)<time()){
         return "exp_date must be in the future";
      }

      return "OK";
   }
}
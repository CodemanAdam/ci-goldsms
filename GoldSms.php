<?php
/**
 * Gold SMS - "29.12.2019"
 * Api URL; https://documenter.getpostman.com/view/6539060/SVtVV8yD?version=latest
 * Site URL; http://goldmesaj.net/
 *
 * Api için gerekli SMS Gönderme yapısı, 2 adet SMS Fonksiyonu vardır. 
 * burada sadece o 2 göndermeyi tetikletme örneğinden bahsettik. 
 * Apinin json olarak göndermek istediği verileri biraz kısıp kullanımını daha basit hale getirmeye çalıştım.
 * SMS Fonksiyonlarını tetiklerken bir array oluşturup bu fonksiyona göndermeniz gerekmektedir.
 * 
 * Array örnekleri alt taraftadır.
 * 
 * 
 * 
 * #### TETİKLEME VE KULLANIM ÖRNEĞİ #####
 *  @@Function SendSMS();
 * $sms_array = array(
 *      "sdate" => "",    // Burada api sistemine istediğimiz tarihi gönderebiliyoruz, amacı o zaman sms atması. Boş bırakılabilir.
 *      "vperiod" => "23", // Burası gönderilen SMS'i kaç saat boyunca göndermeye çalışsın amacından dolayı istenilen bir saat cinsinden bilgidir.
 *      "gate" => 0,  // Burası sürekli 0 gönderilmesi zorundadır. Henüz bir işlevi bulunmamaktadır fakat 0 olarak gönderilmesi isteniliyor.
 *      "message" => array(
 *          "sender" => "ALFANUMERİC", // Burası sisteminizde kayıtlı olan SMS Başlığı girilmesi gerekmektedir. GetAlfanumericList() fonksiyonunu tetikleterek bakabilirsiniz, SMS Başlıklarınıza. 
 *          "text"	=> "Mesaj metni", // Burası SMS gönderilecek metin alanı. İstenilen sayıda karakter gönderilir, sadece gidecek kontör sayısı artar.
 *          "utf8" => "1", // Burası 0 - 1 bilgisi istemekte bizden, 1 ise Türkçe karakterleri olduğu gibi gönderir, 0 ise Türkçe karakterleri düzenler gönderir.
 *          "gsm" => ['0000', '1111'] // Burası mesajın gönderileceği telefon numarası içindir, birden fazla yada bir numara yazarak tetiklenmektedir.
 *      )
 * );
 * 
 *  @@Function MultiSendSMS();
 * 
 * Bu fonksiyon olduğu gibi SendSMS kullanımdaki gibi bir array oluşturup gönderebiliyoruz. 
 * Tek farkı farklı numaralara farklı mesajlar gönderebiliyorsunuz. Bundan dolayı sadece 
 * message arrayı çoğaltılmaktadır.
  * $sms_array = array(
 *      "sdate" => "",    // Burada api sistemine istediğimiz tarihi gönderebiliyoruz, amacı o zaman sms atması. Boş bırakılabilir.
 *      "vperiod" => "23", // Burası gönderilen SMS'i kaç saat boyunca göndermeye çalışsın amacından dolayı istenilen bir saat cinsinden bilgidir.
 *      "gate" => 0,  // Burası sürekli 0 gönderilmesi zorundadır. Henüz bir işlevi bulunmamaktadır fakat 0 olarak gönderilmesi isteniliyor.
 *      "message" => array(
 *          array(
 *              "sender" => "ALFANUMERİC", // Burası sisteminizde kayıtlı olan SMS Başlığı girilmesi gerekmektedir. GetAlfanumericList() fonksiyonunu tetikleterek bakabilirsiniz, SMS Başlıklarınıza. 
 *              "text"	=> "Mesaj metni", // Burası SMS gönderilecek metin alanı. İstenilen sayıda karakter gönderilir, sadece gidecek kontör sayısı artar.
 *              "utf8" => "1", // Burası 0 - 1 bilgisi istemekte bizden, 1 ise Türkçe karakterleri olduğu gibi gönderir, 0 ise Türkçe karakterleri düzenler gönderir.
 *              "gsm" => ['0000'] // Burası mesajın gönderileceği telefon numarası içindir.
 *          ),
 *          array(
 *              "sender" => "ALFANUMERİC", // Burası sisteminizde kayıtlı olan SMS Başlığı girilmesi gerekmektedir. GetAlfanumericList() fonksiyonunu tetikleterek bakabilirsiniz, SMS Başlıklarınıza. 
 *              "text"	=> "2. Mesaj metni", // Burası SMS gönderilecek metin alanı. İstenilen sayıda karakter gönderilir, sadece gidecek kontör sayısı artar.
 *              "utf8" => "1", // Burası 0 - 1 bilgisi istemekte bizden, 1 ise Türkçe karakterleri olduğu gibi gönderir, 0 ise Türkçe karakterleri düzenler gönderir.
 *              "gsm" => ['0000'] // Burası mesajın gönderileceği telefon numarası içindir.
 *          )
 *      )
 * );
 */
class GoldSms {

    private $username = "Gold SMS Kullanıcı Adı";
    private $password = "Gold SMS Kullanıcı Şifresi"; 
    

    /**
     * Fonksiyon : CheckCredit
     * Bu fonksiyon ile önce kullanıcı adımız ve şifremiz ile bakiyemiz olup olmadığını tetikletiyoruz.
     * Bakiyemiz mevcut ise, true dönerek sms tetikleme işlemine olanak sağlıyor.
     */
    function CheckCredit(){

        $request = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $request = json_encode($request);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://apiv3.goldmesaj.net/api/kredi/get",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true) ;

        if($response['status'] == 'ok' && $response['result'] > 1){
            return true;
        } else {
            return false;
        }
    }


    /**
     * Fonksiyon : GetAlfanumericList
     * Bu fonksiyon sistemimizde kayıtlı olan SMS başlık bilgilerimizi betirmektedir.
     */
    function GetAlfanumericList(){
        $return = $this->GetCurl("http://apiv3.goldmesaj.net/api/alfanumerik/getList");
          
        return ($return !== false) ?  $return :  false;
    }

    /**
     * Fonksiyon : GetCurl
     * Bu fonksiyon bizim istediğimiz işlemimizi Curl ile tetiklemeyi sağlar
     * Geri dönmesi olumlu, olumsuz olarak $response değişkenine aktarır.
     */
    function GetCurl($url, $post = null){
        $curl = curl_init();
   
        $request = array(
            'username' => $this->username,
            'password' => $this->password
        );

        if($post != null && is_array($post)){
            $request = array_merge($request, $post);
        }
        
        $request = json_encode($request);
        // print_r($request); //Burayı sil real test ederken
        // exit;              //Burayıda..
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    
    /**
     * Fonksiyon : MultiSendSMS
     * Bu fonksiyon ayrı mesaj yazdırmak istediğiniz anda kullanabilirsiniz. 
     * Detaylı bilgiyi api döküman sitesinden bulabilirsiniz.
     */
    function MultiSendSMS($post){
        $check = $this->CheckCredit();

        if($check != false){
            $return = $this->GetCurl("http://apiv3.goldmesaj.net/api/multiSendSMS", $post);
          
            return ($return !== false) ?  true :  false;
        }
    }


    /**
     * Fonksiyon : SendSMS
     * Bu fonksiyon tek mesaj gönderimi için kullanılır. 
     */
    function SendSMS($post){
        $check = $this->CheckCredit();
        
        if($check != false){
            $return = $this->GetCurl("http://apiv3.goldmesaj.net/api/sendSMS", $post);
          
            return ($return !== false) ?  true :  false;
        }
    }
}

?>
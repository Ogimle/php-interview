<?php
/* Извините за лирическое отступлние, сразу оговорюсь, я не профильный
 * специалист по umi.cms и коммерческого опыта по шаблонам от меня не требовали.
 * Прочитал и читаю Метт Зандстра 5ое издание, начал изучать ларавель, там
 * этого шаблонистого много. Рекрутёр сказал, что задание на пару часиков, но у
 * меня ушло где-то пять вместе с чтением про "солидные" принципы и т.п.
 */



/*
 * раз требуется "простое решение",
 * тогда условие "решение, которое бы позволяло через параметр выбирать способ"
 * понимаю как то, что стратегия выбора лежит на самом классе концепта
 * и в рамках данной задачи не важен способ получения парамера.
 *
 * Тогда пусть значение лежит в синглтоне реестра (Registry).
 * Классы хранищ реализуют KeyStorageInterface в котором опреден метод get
 */

class Concept
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getUserData()
    {
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->getSecretKey()
        ];

        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
        });

        $promise->wait();
    }

    private function getSecretKey()
    {
        $type = AppRegistry::get('SecretKeyStorageType');

        switch($type) {
            case 'file': $storage = new FileKeyStorage(); break;
            default:     $storage = new DbKeyStorage();
        }

        if ($storage instanceof KeyStorageInterface) {
            return $storage->get();
        }

        throw new Exception('Невозможно получить секретный ключ. Некорректный источник.');
    }

    // в подолжении...
    /*
     * хотя, реестр простой класс и если он у нас есть, то задачу ни сколько
     * не усложнит, если мы ранее через замыкания зарегистрируем в нём
     * конструкторы хранилищ
     *
     * AppRegistry::set('FileKeyStorage', function() {
     *      // здесь же и проверку совместимости с интерфейсом делать
     *      return new FileKeyStorage();
     * }
     *
     * тогда получение ключа сведется к
     * $type = AppRegistry::get('SecretKeyStorageType');
     * $key = AppRegistry::get($type)()->get();
     *
     * как это называется пока не знаю, но на днях видел такое конструирование при
     * регистрации сервиса в ларавеле
     */
}

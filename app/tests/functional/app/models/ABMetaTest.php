<?php

namespace app\models;

class ABMetaTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var OpenGraph
	 */
	protected $abm;

	protected function setUp() {
		$this->abm = new ABMeta;
	}

	protected function tearDown() {
		unset($this->abm);
	}

	public function testPopulateWithPlace() {
		$point = new Point();
		$point->setLat('-23.59243454');
		$point->setLng('-46.68677054');

		$city = new City();
		$city->setCountry('BR');
		$city->setState('SP');
		$city->setName('São Paulo');

		$category = new Category();
		$category->setId('067');
		$category->setName('Restaurante');

		$address = new Address();
		$address->setStreet("R. Min. Jesuino Cardoso");
		$address->setNumber(473);
		$address->setCity($city);

		$place = new Place();
		$place->setId("UCV34B2P");
		$place->setName("Uziel Restaurante");
		$place->setDescription("Se você procura um restaurante com variedade, qualidade com preço justo você encontra no Uziel restaurante!O preço do kilo é R$ 26,90, mas você paga no máximo R$ 15,90 por pesagem de refeições (excluindo sobremesas, bebidas e doces). Acima de 500 gramas você ainda ganha um refrescoUm bom vinho, gelatina e cafezinho são por nossa conta.Se precisar de internet você pode contar com nossa rede Wi-Fi.Nosso cardápio diário possui 5 tipos de carne todos os dias, feijoada completa e separada (feijão e carnes) às quartas, 6 tipos de massa nas quintas e 4 tipos de pizzas nassextas, além de opções de peixes todas as terças e sextas.Oferecemos convênio com descontos progressivos para empresas e um bolo com o sabor a escolha do aniversariante, caso agende com antecedência e traga mais de 10 pessoas para almoçar no seu aniversário.Aceitamos todos os cartões de crédito e vales refeição.Você pode receber nosso cardápio atualizado diariamente pelo twitter http://twitter.com/uzielrestaurant");
		$place->setIconUrl("http://maplink.apontador.com.br/widget?v=4.1?v=4.1&lat=-23.5926083&lng=-46.6818329");
		$place->setPoint($point);
		$place->setCategory($category);
		$place->setAddress($address);

		$this->abm->populate($place);

		$rootUrl = \ROOT_URL;

		$testMeta = <<<META
	<meta property="restaurant:title" content="Uziel Restaurante"/>
	<meta property="restaurant:description" content="Se você procura um restaurante com variedade, qualidade com preço justo você encontra no Uziel restaurante!O preço do kilo é R$ 26,90, mas você paga no máximo R$ 15,90 por pesagem de refeições (excluindo sobremesas, bebidas e doces). Acima de 500 gramas você ainda ganha um refrescoUm bom vinho, gelatina e cafezinho são por nossa conta.Se precisar de internet você pode contar com nossa rede Wi-Fi.Nosso cardápio diário possui 5 tipos de carne todos os dias, feijoada completa e separada (feijão e carnes) às quartas, 6 tipos de massa nas quintas e 4 tipos de pizzas nassextas, além de opções de peixes todas as terças e sextas.Oferecemos convênio com descontos progressivos para empresas e um bolo com o sabor a escolha do aniversariante, caso agende com antecedência e traga mais de 10 pessoas para almoçar no seu aniversário.Aceitamos todos os cartões de crédito e vales refeição.Você pode receber nosso cardápio atualizado diariamente pelo twitter http://twitter.com/uzielrestaurant"/>
	<meta property="restaurant:image" content="http://maplink.apontador.com.br/widget?v=4.1?v=4.1&lat=-23.59243454&lng=-46.68677054"/>
	<meta property="restaurant:url" content="{$rootUrl}UCV34B2P/sp/s-o-paulo/restaurante/uziel-restaurante.html"/>
	<meta property="restaurant:address" content="R. Min. Jesuino Cardoso, 473"/>
	<meta property="restaurant:city" content="São Paulo"/>
	<meta property="restaurant:state" content="SP"/>
	<meta property="restaurant:country-name" content="Brasil"/>
	<meta property="restaurant:type" content="restaurant"/>

META;
		$this->assertEquals($testMeta, $this->abm->getMeta());

		$testArray = array(
			'title' => 'Uziel Restaurante',
			'description' => 'Se você procura um restaurante com variedade, qualidade com preço justo você encontra no Uziel restaurante!O preço do kilo é R$ 26,90, mas você paga no máximo R$ 15,90 por pesagem de refeições (excluindo sobremesas, bebidas e doces). Acima de 500 gramas você ainda ganha um refrescoUm bom vinho, gelatina e cafezinho são por nossa conta.Se precisar de internet você pode contar com nossa rede Wi-Fi.Nosso cardápio diário possui 5 tipos de carne todos os dias, feijoada completa e separada (feijão e carnes) às quartas, 6 tipos de massa nas quintas e 4 tipos de pizzas nassextas, além de opções de peixes todas as terças e sextas.Oferecemos convênio com descontos progressivos para empresas e um bolo com o sabor a escolha do aniversariante, caso agende com antecedência e traga mais de 10 pessoas para almoçar no seu aniversário.Aceitamos todos os cartões de crédito e vales refeição.Você pode receber nosso cardápio atualizado diariamente pelo twitter http://twitter.com/uzielrestaurant',
			'image' => 'http://maplink.apontador.com.br/widget?v=4.1?v=4.1&lat=-23.59243454&lng=-46.68677054',
			'url' => ROOT_URL . 'UCV34B2P/sp/s-o-paulo/restaurante/uziel-restaurante.html',
			'address' => 'R. Min. Jesuino Cardoso, 473',
			'city' => 'São Paulo',
			'state' => 'SP',
			'country-name' => 'Brasil',
			'type' => 'restaurant',
		);
		$this->assertEquals($testArray, $this->abm->getArray());
	}

}
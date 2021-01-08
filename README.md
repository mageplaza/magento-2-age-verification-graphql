# Magento Age Verification GraphQL

**Magento Age Verification GraphQL is now a part of the Mageplaza Age Verification extension that adds GraphQL features. This supports PWA Studio.**  

[Mageplaza Age Verification for Magento 2](https://www.mageplaza.com/magento-2-age-verification/) is a useful tool to ensure that your store will not face any problems related to customers’ ages. It makes sure customers who access your website will always be appropriate and authorized to view and purchase your products and services. 

With Mageplaza Age Verification, you can set up age verification for any specific pages, such as CMS pages, Category pages, Product Detail pages, Checkout page, or Catalog Search page. Customers who want to access these pages need to verify their ages to get permission. On the Product page, you can also limit access to some specific products that only certain groups of customers can view and buy. One other great feature of this extension is that you can activate the age verification only when customers make purchases. In particular, when customers go to the Product Detail page, they will be required to verify their ages if they are going to buy a certain product. 

The module speeds up the age verification step by using customers’ dates of birth from their registered accounts as data to verify their ages. Customers can continue viewing a page or browsing your next pages with automatic and quick age verification without performing repetitive verification steps. 

In addition to that, the extension provides you with a dynamic pop-up that effectively notifies customers about the verification process before taking a certain action on your website. This pop-up helps customers not skip or forget to verify their ages. An instant verification via pop-up is way more convenient and exciting for customers to complete their age verification that might be annoying to some people. You can customize this pop-up by modifying the elements, including the type of verification (Yes/No, Input date of birth, or Checkbox), title, description of the pop-up, Confirm/Cancel button, and uploading images as verification icons. Customizing the pop-up in a beautiful and attractive way to draw customers’ attention and appeal to them to accept doing the verification. 

Mageplaza Age Verification for Magento 2 will help you to verify your customers’ ages easily while creating an exceptional experience for them. 

## 1. How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-age-verification-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

**Note:**
Age Verification GraphQL requires installing [Mageplaza Age Verification](https://www.mageplaza.com/magento-2-age-verification/) in your Magento installation.

## 2. How to use

To perform GraphQL queries in Magento, please do the following requirements:

- Use Magento 2.3.x or higher. Set your site to [developer mode](https://www.mageplaza.com/devdocs/enable-disable-developer-mode-magento-2.html).
- Set GraphQL endpoint as `http://<magento2-server>/graphql` in url box, click **Set endpoint**.
  (e.g. `http://dev.site.com/graphql`)
- To view the queries that the **Mageplaza Age Verification GraphQL** extension supports, you can look in `Docs > Query` in the right corner

## 3. Devdocs

- [Magento 2 Age Verification Rest API & examples](https://documenter.getpostman.com/view/10589000/TVK76LAL)
- [Magento 2 Age Verification GraphQL & examples](https://documenter.getpostman.com/view/10589000/TVssi8Bj)

## 4. Contribute to this module

Feel free to **Fork** and contribute to this module. 
You can create a pull request so we will merge your changes main branch.

## 5. Get Support

- Feel free to [contact us](https://www.mageplaza.com/contact.html) if you have any further questions. Our support team is always here to listen to you and solve your problems. 
- if you find this project helpful, please give us a **Star** ![star](https://i.imgur.com/S8e0ctO.png)

import{C as p}from"./connection-13f4ed86.js";import{P as c}from"./product-3cfcfa33.js";import{bn as i,aD as u}from"./index-792b7300.js";async function D({first:o,after:t,filter:r,sort:n}={},{apollo:e}={}){e??(e=u());const{data:{product_connection:s},errors:m,error:E}=await e.query({query:_,variables:{first:o,after:t,filter:r,sort:n}});return p.fromJson({...s,buildNode:d=>c.fromJson(d)})}async function I({id:o},{apollo:t}={}){t??(t=u());const{data:{product:r},errors:n,error:e}=await t.query({query:P,variables:{id:o}});return c.fromJson(r)}async function O({input:o},{apollo:t}={}){t??(t=u());const{data:{product:r},errors:n}=await t.mutate({mutation:f,variables:{input:o}});return c.fromJson(r)}async function A({id:o,input:t},{apollo:r}={}){r??(r=u());const{data:{product:n},errors:e}=await r.mutate({mutation:$,variables:{input:t,id:o}});return c.fromJson(n)}const C=`
id
currency
amount
`,T=`
length
width
height
unit
`,a=`
id
title
description
weight
price{
    ${C}
}
dimension{
    ${T}
}
`,P=i`
      query FETCH_ADDRESS_ITEM($id: Ulid!){
        product: get_product_item(id: $id){
          ${a}
        }
      }
`,_=i`query FETCH_ADDRESS_CONNECTION(
  $first: Int
  $after: String
  $filter: String
  $sort: String
){
  product_connection: get_product_list(
    first: $first
    after: $after
    filter: $filter
    sort: $sort
  ){
    totalCount
    pageInfo{
      hasNextPage
      hasPreviousPage
      startCursor
      endCursor
    }
    edges{
      cursor
      node{
        ${a}
      }
    }
  }
} 
`,f=i`
mutation CREATE_PRODUCT($input: ProductCreationInput!){
  product: createNewProduct(
    input: $input
  ) {
   ${a}
  }
}
`,$=i`
mutation UPDATE_PRODUCT( $id: Ulid! $input: ProductUpdateInput!){
  product: updateProduct(
    id: $id
    input: $input
  ) {
   ${a}
  }
}
`;export{I as a,O as c,D as g,A as u};

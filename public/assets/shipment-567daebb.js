import{C as h}from"./connection-13f4ed86.js";import{a as u}from"./shipment-07eeeb4f.js";import{bn as E,aD as A}from"./index-792b7300.js";import{b as p}from"./address-ff799320.js";async function D({first:t,after:e,filter:i,sort:n}={},{apollo:r}={}){r??(r=A());const{data:{shipment_connection:o},errors:d,error:a}=await r.query({query:y,variables:{first:t,after:e,filter:i,sort:n}});return h.fromJson({...o,buildNode:s=>u.fromJson(s)})}async function F({id:t},{apollo:e}={}){e??(e=A());const{data:{shipment:i},errors:n,error:r}=await e.query({query:R,variables:{id:t}});return u.fromJson(i)}async function q({input:t},{apollo:e}={}){var d,a,c,s;e??(e=A());const i={type:t.type,budget:t.budget,pickupAt:t.pickupAt,deliveryAt:t.deliveryAt,originAddressId:(d=t.originAddress)==null?void 0:d.id,destinationAddressId:(a=t.destinationAddress)==null?void 0:a.id,billingAddressId:(c=t.billingAddress)==null?void 0:c.id,items:(s=t.items)==null?void 0:s.map(m=>{var g;return{productId:(g=m.product)==null?void 0:g.id,quantity:m.quantity,description:m.description}})},{data:{shipment:n},errors:r}=await e.mutate({mutation:_,variables:{input:i}});return u.fromJson(n)}const S=`
    id
    distance
    duration
`,T=`
    id
    firstName
    lastName
    email
    phone
`,$=`
id
currency
amount
`,f=`
length
width
height
unit
`,I=`
id
title
description
price{
    ${$}
}
dimension{
    ${f}
}
`,l=`
    id
    product{
        ${I}
    }
    quantity
    description
`,N=`
id
code
type
status
pickupAt
deliveryAt
billingAddress{
    ${p}
}
originAddress{
    ${p}
}
destinationAddress{
    ${p}
}
items{
    ${l}
}
owner{
    ${T}
}
route{
    ${S}
}
budget{
    id
    currency
    ... on ShipmentFixedBudget{
      price
    }
    ... on ShipmentRangeBudget{
      minPrice
      maxPrice
    }
}
`,R=E`
      query FETCH_ADDRESS_ITEM($id: Ulid!){
        shipment: get_shipment_item(id: $id){
          ${N}
        }
      }
`,y=E`query FETCH_ADDRESS_CONNECTION(
  $first: Int
  $after: String
  $filter: String
  $sort: String
){
  shipment_connection: get_shipment_list(
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
        ${N}
      }
    }
  }
} 
`,_=E`
mutation CRAETE_SHIPMENT($input: ShipmentCreationInput!){
  shipment: createNewShipment(
    input: $input
  ) {
   ${N}
  }
}
`;export{F as a,q as c,D as g};
